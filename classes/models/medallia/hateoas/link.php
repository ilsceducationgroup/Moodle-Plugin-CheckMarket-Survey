<?php

namespace mod_ilsccheckmarket\models\medallia\hateoas;

defined('MOODLE_INTERNAL') || die();

/**
 * Class link
 * @package mod_ilsccheckmarket\models\medallia\hateoas
 * @property string $Href
 * @property string $Title
 * @property string $Method
  */
class link
{
    public $Href;
    public $Title;
    public string $Method;
}

class selfLink extends link
{
    public function __construct(string $href, string $title = null)
    {
        $this->Href = $href;
        $this->Title = $title;
        $this->Method = 'GET';
    }
}

class createLink extends link
{
    public function __construct(string $href, string $title = null)
    {
        $this->Href = $href;
        $this->Title = $title;
        $this->Method = 'POST';
    }
}

class updateLink extends link
{
    public function __construct(string $href, string $title = null)
    {
        $this->Href = $href;
        $this->Title = $title;
        $this->Method = 'PUT';
    }
}

class deleteLink extends link
{
    public function __construct(string $href, string $title = null)
    {
        $this->Href = $href;
        $this->Title = $title;
        $this->Method = 'DELETE';
    }
}

class nextLink extends link
{
    public function __construct(string $href, string $title = null)
    {
        $this->Href = $href;
        $this->Title = $title;
        $this->Method = 'GET';
    }
}

class previousLink extends link
{
    public function __construct(string $href, string $title = null)
    {
        $this->Href = $href;
        $this->Title = $title;
        $this->Method = 'GET';
    }
}

class helpLink extends link
{
    public function __construct(string $href, string $title = null)
    {
        $this->Href = $href;
        $this->Title = $title;
        $this->Method = 'GET';
    }
}

class progressLink extends link
{
    public function __construct(string $href, string $title = null)
    {
        $this->Href = $href;
        $this->Title = $title;
        $this->Method = 'GET';
    }
}

class alternateLink extends link
{
    public function __construct(string $href, string $title = null)
    {
        $this->Href = $href;
        $this->Title = $title;
        $this->Method = 'GET';
    }
}

class listLink extends link
{
    public function __construct(string $href, string $title = null)
    {
        $this->Href = $href;
        $this->Title = $title;
        $this->Method = 'GET';
    }
}
