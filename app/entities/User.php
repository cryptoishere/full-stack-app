<?php

namespace entities;

class User
{
    /** @readonly **/
    public $id;
    /** @readonly **/
    public $username;

    public function __construct(string $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }
}