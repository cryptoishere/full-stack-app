<?php

namespace entities;

class User
{
    /** @readonly **/
    public $id;
    /** @readonly **/
    public $username;
    /** @readonly **/
    public $pin;

    public function __construct(string $id, string $username, int $pin)
    {
        $this->id = $id;
        $this->username = $username;
        $this->pin = $pin;

    }
}