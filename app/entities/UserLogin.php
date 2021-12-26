<?php

namespace entities;

class UserLogin
{
    public function __construct(
        public readonly string  $id,
        // public readonly string $session_id,
        public readonly string $pubkey,
        public readonly string $username,
        public readonly int $pin,
        // public readonly int $account_type,
        // public readonly string $create_time,
        // public readonly string $update_time,
    )
    {
        
    }
}