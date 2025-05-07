<?php

namespace mod_ilsccheckmarket\services;

defined('MOODLE_INTERNAL') || die();

use mod_ilsccheckmarket\models\medallia\respondent\respondents AS model_respondents;

class respondents extends api
{
    public function __construct($masterkey, $key, $surveyid, $respondentid = null)
    {
        parent::__construct($masterkey, $key);
        $this->base_url = "https://api-ca.agileresearch.medallia.com/3/surveys/$surveyid/respondents" . ($respondentid ? "/$respondentid" : '');
    }

    public function get_respondents(?string $respondentid = null): model_respondents
    {
        if ($respondentid) {
            $this->base_url .= "/$respondentid";
        }
        $data = $this->get_data();
        return new model_respondents($data);
    }
}
