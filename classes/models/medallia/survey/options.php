<?php

namespace mod_ilsccheckmarket\models\medallia\survey;

defined('MOODLE_INTERNAL') || die();

class options
{
    public ?bool $AllowEditingResponses;
    public ?bool $AllowMultipleSubmissions;
    public ?int $MaximumRespondents;
    public ?int $MaximumCompletes;
    public ?bool $AnonymousPanel;
    public ?int $CooldownInDaysAfterInvitation;
    public ?int $CooldownInDaysAfterResponse;
    public ?bool $ShowQuestionNumbering;
    public ?float $ScoreMax;

    public function __construct(?object $options)
    {
        $this->AllowEditingResponses = $options->AllowEditingResponses ?? null;
        $this->AllowMultipleSubmissions = $options->AllowMultipleSubmissions ?? null;
        $this->MaximumRespondents = $options->MaximumRespondents ?? null;
        $this->MaximumCompletes = $options->MaximumCompletes ?? null;
        $this->AnonymousPanel = $options->AnonymousPanel ?? null;
        $this->CooldownInDaysAfterInvitation = $options->CooldownInDaysAfterInvitation ?? null;
        $this->CooldownInDaysAfterResponse = $options->CooldownInDaysAfterResponse ?? null;
        $this->ShowQuestionNumbering = $options->ShowQuestionNumbering ?? null;
        $this->ScoreMax = $options->ScoreMax ?? null;
    }
}
