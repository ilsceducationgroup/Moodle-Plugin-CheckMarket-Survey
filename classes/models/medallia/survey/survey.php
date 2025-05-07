<?php

namespace mod_ilsccheckmarket\models\medallia\survey;

use mod_ilsccheckmarket\models\medallia\respondent\respondents;

defined('MOODLE_INTERNAL') || die();

class survey
{
    public ?int $Id;
    public ?int $SurveyId;
    public ?string $Title;
    public ?int $SurveyStatusId;
    public ?string $CreateDate;
    public ?string $LastModifyDate;
    public ?string $StartDate;
    public ?string $EndDate;
    public ?bool $IsTrial;
    public ?int $PanelistCount;
    public ?int $RespondentCount;
    public ?string $CreatedBy;
    public ?int $QuestionCount;
    public ?channels $Channels;
    public ?options $Options;
    public ?array $Questions;
    public ?string $DefaultLang;
    public ?string $ClientRef;
    public ?int $SurveyFolderId;
    public ?string $PanelistStatusId;
    public ?string $DateAdded;
    public ?string $PanelistReportUrl;
    public ?respondents $Respondents;

    public function __construct(object $survey)
    {
        $vars = get_class_vars(get_class($this));
        unset($vars['Channels']);
        unset($vars['Options']);
        unset($vars['Respondents']);

        foreach ($vars as $key => $value) {
            $this->$key = $survey->$key ?? null;
        }

        if ($survey->Channels ?? false) {
            $this->Channels = new channels($survey->Channels);
        }
        if ($survey->Options ?? false) {
            $this->Options = new options($survey->Options);
        }
        if ($survey->Respondents ?? false) {
            $this->Respondents = new respondents($survey->Respondents);
        }
    }
}
