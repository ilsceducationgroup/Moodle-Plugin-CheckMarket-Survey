<?php

namespace mod_ilsccheckmarket\services;

use mod_ilsccheckmarket\models\medallia\lookup\lookup;
use mod_ilsccheckmarket\models\medallia\panels\survey\row;
use stdClass;

defined('MOODLE_INTERNAL') || die();

class panel_surveys
{
    public string $masterkey;
    public string $xkey;
    public string $email = '';
    public string $ilscnumber = '';

    private contacts $service_contacts;
    private surveys $service_surveys;

    public array $data = [];

    public function __construct($masterkey, $xkey)
    {
        $this->masterkey = $masterkey;
        $this->xkey = $xkey;
        $this->service_contacts = new contacts($masterkey, $xkey);
        $this->service_contacts->expand_surveys();

        $this->service_surveys = new surveys($masterkey, $xkey);
    }

    public function set_email(string $email)
    {
        $this->email = $email;
        $this->service_contacts->add_filter('Email', 'eq', "'$email'", 'or');
    }

    public function set_ilscnumber(string $ilscnumber)
    {
        $this->ilscnumber = $ilscnumber;
        $this->service_contacts->add_filter("substringof(CustomField1,'$ilscnumber')", 'eq', 'true', 'or');
    }

    public function get_panel_data(): array
    {
        $panel_data = [];

        if (!$this->email && !$this->ilscnumber) {
            return [];
        }

        $contacts = $this->service_contacts->get_contacts();

        if ($contacts->Meta->Status !== 200) {
            $error = $contacts->Data;
            $errorCode = $error->ErrorCode;
            $errorMessage = $error->ErrorMessage;
            $errorType = $error->ErrorType;
            $message = "Code: $errorCode<br>Message: $errorMessage<br>Type: $errorType";
            $message .= "<br>URL: " . $this->service_contacts->get_api_full_url();

            throw new \Exception("Error: " . $message);
        }

        $contactsData = $contacts->Data;
        if (!$contactsData) {
            return [];
        }

        foreach ($contactsData as $contact) {
            $surveys = $contact->Surveys;
            $contact_id = $contact->ContactId;
            foreach ($surveys as $survey) {
                $survey_id = $survey->SurveyId;
                $service_survey_contacts = new survey_contacts($this->masterkey, $this->xkey, $survey_id);
                $survey_contacts = $service_survey_contacts->get_survey_contacts($contact_id);

                $survey_details = $this->service_surveys->get_survey($survey_id);
                // Only include surveys with status "Live" (ID = 2) to strictly enforce only showing Live surveys
                if ($survey_details->SurveyStatusId !== 2) {
                    continue; // Skip non-Live surveys
                }
                $data = $survey_contacts->Data;
                $row = new row();
                $row->SurveyId = $survey_id;
                $row->SurveyStatusId = $survey_details->SurveyStatusId;
                $row->SurveyStatus = lookup::surveyStatus[$survey_details->SurveyStatusId] ?? '';
                $row->StartDate = $survey_details->StartDate;
                $row->EndDate = $survey_details->EndDate;
                $row->StudentNumber = $data->CustomField1;
                $row->StudentEmail = $data->Email;
                $row->Title = $survey->Title;
                $row->PreviewUrl = $data->PreviewUrl;
                $row->MailPreviewUrl = $data->MailPreviewUrl;
                $row->LiveUrl = $data->LiveUrl;
                $row->ContactStatusId = $data->ContactStatusId;
                $row->ContactStatus = lookup::contactStatus[$data->ContactStatusId] ?? '';
                $row->DateAdded = $data->DateAdded;
                $row->DateInvited = $data->DateInvited;
                $row->DateSawMail = $data->DateSawMail;
                $row->DateClickedThrough = $data->DateClickedThrough;
                $row->DateReminded = $data->DateReminded;
                $row->DateRemindedPartial = $data->DateRemindedPartial;
                $row->DateResponded = $data->DateResponded;
                $row->PanelistReportUrl = $survey->PanelistReportUrl ?? null;
                $panel_data[] = $row;
            }
        }
        return $panel_data;
    }
}
