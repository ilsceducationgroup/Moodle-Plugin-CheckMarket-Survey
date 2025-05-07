<?php

namespace mod_ilsccheckmarket\models\medallia\survey_contact;

defined('MOODLE_INTERNAL') || die();

use mod_ilsccheckmarket\models\medallia\defaultApi\defaultApi;

class survey_contacts extends defaultApi
{
    public $Data;
    public $Meta;
    public $Links;

    public function __construct(object $json_response)
    {
        parent::__construct($json_response);

        if ($this->Meta->Status !== 200) {
            return;
        }

        $data = $json_response->Data;
        if (is_array($data))
        {
            foreach ($json_response->Data as $data) {
                if (!$data || !is_object($data)) {
                    continue;
                }
                $this->Data[] = new survey_contact($data);
            }
        }

        if (is_object($data))
        {
            $this->Data = new survey_contact($data);
        }

    }
}
