<?php

namespace mod_ilsccheckmarket\services;

defined('MOODLE_INTERNAL') || die();

use mod_ilsccheckmarket\models\medallia\contact\contacts AS model_contacts;

class distribution extends api
{
    //https://ca.agileresearch.medallia.com/2/survey/120483/distribute/GetPanel/
    //?d=e&sort=ContactId&order=desc&offset=0&limit=25
    //&filterstatus=&filterfield=Email&filterfieldselector=equals&filterfieldvalue=&filterfieldvalue2
    //=&filtercontactgroup=&_=1721925729286

    public $base_url = 'https://ca.agileresearch.medallia.com/2/survey/{#surveyid#}/GetPanel';

    public function get_distribution()
    {
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
