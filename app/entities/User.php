<?php

namespace entities;

class User
{
    /** @readonly **/
    public $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}