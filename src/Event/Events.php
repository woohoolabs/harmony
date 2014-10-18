<?php
namespace WoohooLabs\ApiFramework\Event;

class Events
{
    const BEFORE_RECEIVING_REQUEST= "request.before";
    const AFTER_RECEIVING_REQUEST= "request.after";
    const AFTER_DISCOVERY= "discovery.after";
    const AFTER_ROUTING= "routing.after";
    const BEFORE_SENDING_RESPONSE= "response.before";
}
