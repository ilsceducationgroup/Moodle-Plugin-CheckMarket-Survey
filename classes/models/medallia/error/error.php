<?php

namespace mod_ilsccheckmarket\models\medallia\error;

use mod_ilsccheckmarket\models\medallia\defaultApi\defaultApi;
use mod_ilsccheckmarket\models\medallia\hateoas\hateoas;

defined('MOODLE_INTERNAL') || die();

class error
{
    public ?int $ErrorCode;
    public ?string $ErrorMessage;
    public ?string $ErrorType;

    public function __construct(?object $json_error = null)
    {
        $this->ErrorCode = $json_error->ErrorCode ?? null;
        $this->ErrorMessage = $json_error->ErrorMessage ?? null;
        $this->ErrorType = $json_error->ErrorType ?? null;
    }
}
