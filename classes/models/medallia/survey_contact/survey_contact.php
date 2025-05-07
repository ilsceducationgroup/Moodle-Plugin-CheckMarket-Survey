<?php

namespace mod_ilsccheckmarket\models\medallia\survey_contact;

use mod_ilsccheckmarket\models\medallia\hateoas\hateoas;

defined('MOODLE_INTERNAL') || die();

class survey_contact
{
    public ?string $PreviewUrl;
    public ?string $LiveUrl;
    public ?string $MailPreviewUrl;
    public ?int $OptoutSourceId;
    public ?string $ReportUrl;
    public ?int $ContactStatusId;
    public ?string $DateAdded;
    public ?string $DateInvited;
    public ?string $DateSawMail;
    public ?string $DateClickedThrough;
    public ?string $DateReminded;
    public ?string $DateRemindedPartial;
    public ?string $DateResponded;
    public ?string $Password;
    public ?string $DateSentThankYouMail;
    public ?string $DateToBeInvited;
    public ?string $DateSecondReminder;
    public ?string $DateToExpire;
    public ?string $DateLastModified;
    public ?int $ContactId;
    public ?string $FirstName;
    public ?string $LastName;
    public ?string $Email;
    public ?string $LangCode;
    public ?string $CustomField1;
    public ?string $CustomField2;
    public ?string $CustomField3;
    public ?string $CustomField4;
    public ?string $CustomField5;
    public ?string $CustomField6;
    public ?string $CustomField7;
    public ?string $CustomField8;
    public ?string $CustomField9;
    public ?string $CustomField10;
    public ?string $CustomField11;
    public ?string $CustomField12;
    public ?string $CustomField13;
    public ?string $CustomField14;
    public ?string $CustomField15;
    public ?string $CustomField16;
    public ?string $CustomField17;
    public ?string $CustomField18;
    public ?string $CustomField19;
    public ?string $CustomField20;
    public ?string $Street;
    public ?string $HouseNumber;
    public ?string $Suite;
    public ?string $PostalCode;
    public ?string $City;
    public ?string $State;
    public ?string $Province;
    public ?string $Phone;
    public ?string $CountryId;
    public ?string $Gender;
    public ?string $DateOfBirth;
    public ?bool $IsBounced;
    public ?bool $IsOptedOut;
    public ?hateoas $Links;

    public function __construct(object $survey_contact)
    {
        $vars = get_class_vars(get_class($this));
        unset($vars['Links']);

        foreach ($vars as $key => $value) {
            $this->$key = $survey_contact->$key ?? null;
        }

        if ($survey_contact->Links ?? false) {
            $this->Links = new hateoas($survey_contact->Links);
        }
    }
}
