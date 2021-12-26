<?php

namespace repository;

use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;

use entities\User;
use entities\UserLogin;

class UserRepository
{
    private $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /** @return PromiseInterface<?User> **/
    public function query(string $sql, array $params = []): PromiseInterface
    {
        return $this->db->query($sql, $params)->then(function (QueryResult $result) {
            if (!$result ) {
                return null;
            }

            if (count($result->resultRows ?? []) === 0) {
                return null;
            }

            return new User(
                $result->resultRows[0]['id'],
                $result->resultRows[0]['username'],
                $result->resultRows[0]['pin']
            );
        });
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

    /** @return PromiseInterface<?UserLogin> **/
    public function findUserByAddress(string $address): PromiseInterface
    {
        return $this->db->query(
            'SELECT id, pubkey, username, pin FROM users WHERE username = ?', [$address]
        )->then(function (QueryResult $result) {
            if (count($result->resultRows) === 0) {
                return null;
            }

            return new UserLogin(
                $result->resultRows[0]['id'],
                $result->resultRows[0]['pubkey'],
                $result->resultRows[0]['username'],
                $result->resultRows[0]['pin']
            );
        });
    }
}