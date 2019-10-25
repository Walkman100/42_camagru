<?php

include_once("setup.php");
include_once("output.php");

/**
 * @param string    $name   User to check
 * @return bool             True if user exists
 */
function user_exists(string $name)
{
    $stmt = DB::prepare("SELECT * FROM `users` WHERE username = :name");
    if ($stmt->execute(array('name' => $name)))
    {
        if ($stmt->fetchAll())
        {
            $stmt = null;
            return (true);
        }
        else
        {
            $stmt = null;
            return (false);
        }
    }
    else
    {
        $stmt = null;
        print("Error checking if user exists");
        exit;
    }
}

/**
 * @param string    $email  Email address to check
 * @return bool             True if email is already registered
 */
function email_used(string $email)
{
    $stmt = DB::prepare("SELECT * FROM `users` WHERE email = :email");
    if ($stmt->execute(array('email' => $email)))
    {
        if ($stmt->fetchAll())
        {
            $stmt = null;
            return (true);
        }
        else
        {
            $stmt = null;
            return (false);
        }
    }
    else
    {
        $stmt = null;
        print("Error checking for email address existance");
        exit;
    }
}

/**
 * @param string    $name       Username to check
 * @param string    $password   Password to check
 * @return bool                 True if password is correct
 */
function correct_pw(string $name, string $password)
{
    $stmt = DB::prepare("SELECT * FROM `users` WHERE username = :name");
    if ($stmt->execute(array('name' => $name)))
    {
        if (($return = $stmt->fetchAll()) && $return[0]["password"] === hash("whirlpool", $password))
        {
            $stmt = null;
            return (true);
        }
        else
        {
            $stmt = null;
            return (false);
        }
    }
    else
    {
        $stmt = null;
        print("Error checking if password is correct");
        exit;
    }
}

?>
