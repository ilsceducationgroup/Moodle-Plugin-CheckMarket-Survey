<?php

namespace mod_ilsccheckmarket\models\medallia\survey;

defined('MOODLE_INTERNAL') || die();

class channels
{
    public ?channel $ViaEmail;
    public ?channel $ViaWeb;
    public ?channel $ViaPaper;
    public ?channel $ViaTelephone;
    public ?channel $ViaKiosk;
    public ?channel $ViaSms;
    public ?channel $ViaPanelProvider;

    public function __construct(object $channels)
    {
        $init = function(?object $channel) {
            if (isset($channel) && isset($channel->IsActive)) {
                return new channel($channel->IsActive);
            }
            return null;
        };

        $this->ViaEmail = $init($channels->ViaEmail);
        $this->ViaWeb = $init($channels->ViaWeb);
        $this->ViaPaper = $init($channels->ViaPaper);
        $this->ViaTelephone = $init($channels->ViaTelephone);
        $this->ViaKiosk = $init($channels->ViaKiosk);
        $this->ViaSms = $init($channels->ViaSms);
        $this->ViaPanelProvider = $init($channels->ViaPanelProvider);
    }
}
