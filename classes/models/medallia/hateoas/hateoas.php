<?php

namespace mod_ilsccheckmarket\models\medallia\hateoas;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/link.php');

class hateoas
{
    public ?selfLink $Self;
    public ?createLink $Create;
    public ?updateLink $Update;
    public ?deleteLink $Delete;
    public ?nextLink $Next;
    public ?previousLink $Previous;
    public ?helpLink $Help;
    public ?progressLink $Progress;
    public ?alternateLink $AlternateLink;
    public ?listLink $ListLink;
    public ?array $Other;

}
