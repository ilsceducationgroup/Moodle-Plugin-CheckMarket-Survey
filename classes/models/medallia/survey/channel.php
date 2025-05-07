<?php

namespace mod_ilsccheckmarket\models\medallia\survey;

defined('MOODLE_INTERNAL') || die();

class channel
{
    public bool $IsActive;

    public function __construct(bool $isActive = true) {
        $this->IsActive = $isActive;
    }
}
