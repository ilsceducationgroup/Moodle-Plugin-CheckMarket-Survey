<?php

namespace mod_ilsccheckmarket\models\medallia\defaultApi;

defined('MOODLE_INTERNAL') || die();

use mod_ilsccheckmarket\models\medallia\error\error;
use mod_ilsccheckmarket\models\medallia\metadata\metadata;
use mod_ilsccheckmarket\models\medallia\hateoas\hateoas;

abstract class defaultApi
{

    public $Data;
    public $Meta;
    public $Links;

    public function __construct(object $json_response)
    {

        if (!is_object($json_response)) {
            throw new \Exception('Invalid JSON response');
        }

        $this->Meta = new metadata(json_encode($json_response->Meta));
        
        if (isset($json_response->Links)) {
            $this->Links = new hateoas(json_encode($json_response->Links));
        }

        if ($this->Meta->Status !== 200) {
            if (is_string($json_response->Data)) {
                $error = new error();
                $error->ErrorMessage = $json_response->Data;
                $this->Data = $error;
                return;
            }
            $this->Data = new error($json_response->Data);
            return;
        }
    }
}
