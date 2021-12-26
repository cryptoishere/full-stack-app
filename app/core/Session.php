<?php

namespace core;

class Session
{
    public const SESSION_LIFETIME = 3600;
    /**
     * Prefix for sessions.
     *
     * @var string
     */
    private static $prefix = 'live_';

    /**
     * Determine if session has started.
     *
     * @var bool
     */
    private static $sessionStarted = false;

    /**
     * Set prefix for sessions.
     *
     * @param mixed $prefix → prefix for sessions
     *
     * @return bool
     */
    public static function setPrefix($prefix)
    {
        return is_string(self::$prefix = $prefix);
    }

    /**
     * Get prefix for sessions.
     *
     * @since 1.1.6
     *
     * @return string
     */
    public static function getPrefix()
    {
        return self::$prefix;
    }

    /**
     * If session has not started, start sessions.
     *
     * @param int $lifeTime → lifetime of session in seconds
     *
     * @return bool
     */
    public static function init($lifeTime = 0)
    {
        if (self::$sessionStarted == false) {
            session_name('STHSESSION');
            session_set_cookie_params($lifeTime);
            session_start();

            return self::$sessionStarted = true;
        }

        return false;
    }

    /**
     * Add value to a session.
     *
     * @param string $key   → name the data to save
     * @param mixed  $value → the data to save
     *
     * @return bool true
     */
    public static function set($key, $value = false)
    {
        if (is_array($key) && $value == false) {
            foreach ($key as $name => $value) {
                $_SESSION[self::$prefix . $name] = $value;
            }
        } else {
            $_SESSION[self::$prefix . $key] = $value;
        }

        return true;
    }

    /**
     * Extract session item, delete session item and finally return the item.
     *
     * @param string $key → item to extract
     *
     * @return mixed|null → return item or null when key does not exists
     */
    public static function pull($key)
    {
        if (isset($_SESSION[self::$prefix . $key])) {
            $value = $_SESSION[self::$prefix . $key];
            unset($_SESSION[self::$prefix . $key]);

            return $value;
        }

        return null;
    }

    /**
     * Get item from session.
     *
     * @param string      $key       → item to look for in session
     * @param string|bool $secondkey → if used then use as a second key
     *
     * @return mixed|null → key value, or null if key doesn't exists
     */
    public static function get($key = '', $secondkey = false)
    {
        $name = self::$prefix . $key;

        if (empty($key)) {
            return isset($_SESSION) ? $_SESSION : null;
        } elseif ($secondkey == true) {
            if (isset($_SESSION[$name][$secondkey])) {
                return $_SESSION[$name][$secondkey];
            }
        }

        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }

    /**
     * Get session id.
     *
     * @return string → the session id or empty
     */
    public static function id()
    {
        return session_id();
    }

    /**
     * Regenerate session_id.
     *
     * @return string → session_id
     */
    public static function regenerate()
    {
        session_regenerate_id(true);

        return session_id();
    }

    /**
     * Empties and destroys the session.
     *
     * @param string $key    → session name to destroy
     * @param bool   $prefix → if true clear all sessions for current prefix
     *
     * @return bool
     */
    public static function destroy($key = '', $prefix = false)
    {
        if (self::$sessionStarted == true) {
            if ($key == '' && $prefix == false) {
                session_unset();
                session_destroy();
            } elseif ($prefix == true) {
                foreach ($_SESSION as $index => $value) {
                    if (strpos($index, self::$prefix) === 0) {
                        unset($_SESSION[$index]);
                    }
                }
            } else {
                unset($_SESSION[self::$prefix . $key]);
            }

            return true;
        }

        return false;
    }

    public static function userIsLoggedIn()
    {
        return self::get('user_authenticated') ? true : false;
    }

    public static function isSessionStarted()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public static function isConcurrentSessionExists(): bool
    {
        $session_id = session_id();
        $sessionAddress = Session::get('user_authenticated');

        // if (isset($sessionAddress) && isset($session_id)) {
        //     $db = DBFactory::getConnection();

        //     $sql = 'SELECT session_id FROM users WHERE username = ?';

        //     $response = true;

        //     $db->query($sql, [$sessionAddress])->then(function (QueryResult $result) use ($session_id) {
        //         if (count($result->resultRows) === 0) {
        //             return false;
        //         }

        //         return $session_id !== (string)$result->resultRows[0]['session_id'];
        //     })->then(function (bool $result) use (&$res) {
        //         $res = $result;
        //     });

        //     return $response;
        // }

        return false;
    }
}