<?php

namespace repository;

use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;

use entities\User;

class UserRepository
{
    private $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /** @return PromiseInterface<?User> **/
    public function findUser(int $id): PromiseInterface
    {
        return $this->db->query(
            'SELECT id, username, pin FROM users WHERE id = ?', [$id]
        )->then(function (QueryResult $result) {
            if (count($result->resultRows) === 0) {
                return null;
            }

            return new User(
                $result->resultRows[0]['id'],
                $result->resultRows[0]['username'],
                $result->resultRows[0]['pin']
            );
        });
    }
}