<?php

namespace mod_ilsccheckmarket\models\medallia\survey;

defined('MOODLE_INTERNAL') || die();

class question
{
    public int $Id;
    public string $DataLabel;
    public string $Language;
    public int $QuestionTypeId;
    public string $Caption;
    public bool $Required;
    public int $DataTypeId;
    public int $ScaleTypeId;
    public bool $Hidden;
    public int $MinValue;
    public int $MaxValue;
    public int $PageNumber;
    public int $OrderNumber;
    public int $QuestionNumber;
    public int $ParentQuestionId;
    public bool $UseSentimentScore;
    public array $SubQuestions;

}
