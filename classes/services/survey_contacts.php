<?php

namespace mod_ilsccheckmarket\services;

defined('MOODLE_INTERNAL') || die();

use mod_ilsccheckmarket\models\medallia\survey_contact\survey_contacts AS model_survey_contacts;

class survey_contacts extends api
{
    public $base_url = 'https://api-ca.agileresearch.medallia.com/3/surveys';
    public $survey_id;
    public $contact_id;

    public function __construct($masterkey, $xkey, $survey_id)
    {
        parent::__construct($masterkey, $xkey);
        $this->survey_id = $survey_id;
    }

    public function get_survey_contacts(?int $contact_id = null)
    {
        $this->add_route($this->survey_id);
        $this->add_route('contacts');
        if ($this->contact_id) {
            $this->add_route($this->contact_id);
        }
        $contactId = $contact_id ?? $this->contact_id ?? null;
        if ($contactId) {
            $this->add_route($contactId);
        }

        return new model_survey_contacts($this->get_data());
    }
}
