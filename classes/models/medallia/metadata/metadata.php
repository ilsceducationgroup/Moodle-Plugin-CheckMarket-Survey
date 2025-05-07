<?php

namespace mod_ilsccheckmarket\models\medallia\metadata;

use mod_ilsccheckmarket\models\medallia\error\error;

defined('MOODLE_INTERNAL') || die();

class metadata
{
    public $Status;
    public $TotalRowCount;
    public $Limit;
    public $Offset;
    public $TotalPageCount;
    public $StartRec;
    public $StopRec;
    public $Timestamp;
    public $IsFiltered;
    public $Data;

    public function __construct(string $metadata)
    {
        $json_metadata = json_decode($metadata);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response');
        }

        $this->Status = $json_metadata->Status;
        $this->Timestamp = $json_metadata->Timestamp;

        if ($this->Status !== 200) {
            return;
        }

        $this->TotalRowCount = $json_metadata->TotalRowCount ?? null;
        $this->Limit = $json_metadata->Limit ?? null;
        $this->Offset = $json_metadata->Offset ?? null;
        $this->TotalPageCount = $json_metadata->TotalPageCount ?? null;
        $this->StartRec = $json_metadata->StartRec ?? null;
        $this->StopRec = $json_metadata->StopRec ?? null;
        $this->IsFiltered = $json_metadata->IsFiltered ?? null;
        $this->Data = [];
    }

}
