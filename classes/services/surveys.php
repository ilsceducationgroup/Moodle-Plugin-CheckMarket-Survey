<?php

namespace mod_ilsccheckmarket\services;

defined('MOODLE_INTERNAL') || die();

use mod_ilsccheckmarket\models\medallia\survey\surveys AS model_surveys;
use mod_ilsccheckmarket\models\medallia\survey\survey AS model_survey;

class surveys extends api
{
    public $base_url = 'https://api-ca.agileresearch.medallia.com/3/surveys';
    
    public function get_surveys(): ?model_surveys
    {
        $data = $this->get_data();

        if (empty($data) || !is_object($data)) {
            return null;
        }
        return new model_surveys($data);
    }

    public function get_survey(int $survey_id): ?model_survey
    {
        $this->reset_routes();
        $this->add_route($survey_id);
        $data = $this->get_data();

        if (empty($data) || !is_object($data)) {
            return null;
        }
        return new model_survey($data->Data);
    }

    public function expand_respondents(): void
    {
        if (!empty($this->expand)){
            $this->expand .= ',';
        }
        $this->expand .= 'Respondents';
    }
}
