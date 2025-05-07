<?php

namespace mod_ilsccheckmarket\models\medallia\panels\survey;

defined('MOODLE_INTERNAL') || die();

class row
{
    public int $SurveyId;
    public ?int $SurveyStatusId;
    public ?string $SurveyStatus;
    public ?string $StartDate;
    public ?string $EndDate;
    public ?string $StudentNumber;
    public ?string $StudentEmail;
    public ?string $Title;
    public ?string $PreviewUrl;
    public ?string $LiveUrl;
    public ?string $MailPreviewUrl;
    public ?int $ContactStatusId;
    public ?string $ContactStatus;
    public ?string $DateAdded;
    public ?string $DateInvited;
    public ?string $DateSawMail;
    public ?string $DateClickedThrough;
    public ?string $DateReminded;
    public ?string $DateRemindedPartial;
    public ?string $DateResponded;
}
