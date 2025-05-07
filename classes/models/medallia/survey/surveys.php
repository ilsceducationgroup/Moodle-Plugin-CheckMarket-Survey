<?php

namespace mod_ilsccheckmarket\models\medallia\survey;

defined('MOODLE_INTERNAL') || die();

use mod_ilsccheckmarket\models\medallia\defaultApi\defaultApi;

class surveys extends defaultApi
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

        foreach ($json_response->Data as $data) {
            if (!$data || !is_object($data)) {
                continue;
            }
            $this->Data[] = new survey($data);
        }
    }
}
