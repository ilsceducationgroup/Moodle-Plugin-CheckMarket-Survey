<?php

namespace mod_ilsccheckmarket\services;

defined('MOODLE_INTERNAL') || die();

use mod_ilsccheckmarket\models\medallia\contact\contacts AS model_contacts;

class contacts extends api
{
    public $base_url = 'https://api-ca.agileresearch.medallia.com/3/contacts';

    public function get_contacts(): model_contacts
    {
        return new model_contacts($this->get_data());
    }

    public function expand_surveys(): void
    {
        if (!empty($this->expand)){
            $this->expand .= ',';
        }
        $this->expand .= 'Surveys';
    }

    public function expand_groups(): void
    {
        if (!empty($this->expand)){
            $this->expand .= ',';
        }
        $this->expand .= 'Groups';
    }
}
