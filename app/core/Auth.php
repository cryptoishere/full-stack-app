<?php

namespace core;

use Exception;
use React\MySQL\QueryResult;

class Auth
{
    public static function setSessionId($address): bool
    {
        $db = DBFactory::getConnection();
        $sesId = session_id();

        $sql = "UPDATE users SET session_id = ? WHERE username = ?";

        $res = true;

        $prom = $db->query($sql, [$sesId, $address])->then(function (QueryResult $result) {
            if ($result->insertId !== 0) {
                return true;
            }

            return true;
        }, function (Exception $error): bool
        {
            return true;
        });

        // TODO: fiex response
        return $res;
    }
}
