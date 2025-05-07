<?php

namespace mod_ilsccheckmarket\models\medallia\respondent;

defined('MOODLE_INTERNAL') || die();

use mod_ilsccheckmarket\models\medallia\defaultApi\defaultApi;

class respondents extends defaultApi
{
    public function __construct(object $json_response)
    {
        parent::__construct($json_response);

        if ($this->Meta->Status !== 200) {
            return;
        }

        foreach ($json_response->Data as $data) {
            $this->Data[] = new respondent($data);
        }
    }
}
