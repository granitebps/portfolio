<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class LogApiEvent extends ShouldBeStored
{
    public $date;
    public $url;
    public $method;
    public $body;
    public $header;
    public $ip;
    public $code;

    public function __construct(
        $date,
        $url,
        $method,
        $body,
        $header,
        $ip,
        $code
    ) {
        $this->date = $date;
        $this->url = $url;
        $this->method = $method;
        $this->body = $body;
        $this->header = $header;
        $this->ip = $ip;
        $this->code = $code;
    }
}
