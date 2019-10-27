<?php

include_once("setup.php");
include_once("output.php");
include_once("func_email.php");

// ====================== return bool functions ======================

/**
 * @param string    $name   User to check
 * @return bool             True if user exists
 */
function user_exists(string $name)
{
    $stmt = DB::prepare("SELECT * FROM `users` WHERE `username` = :name");
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
 * @param string    $name       Username to check
 * @param string    $password   Password to check
 * @return bool                 True if password is correct
 */
function correct_pw(string $name, string $password)
{
    $stmt = DB::prepare("SELECT * FROM `users` WHERE `username` = :name");
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

/**
 * @param string    $email  Email address to check
 * @return bool             True if email is already registered
 */
function email_used(string $email)
{
    $stmt = DB::prepare("SELECT * FROM `users` WHERE `email` = :email");
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
 * @return bool                 True if account has the notify email option on
 */
function notify_is_on(string $name)
{
    $stmt = DB::prepare("SELECT `notify` FROM `users` WHERE `username` = :name");
    if (!$stmt->execute(array('name' => $name)))
    {
        $stmt = null;
        print("Error checking if the account is active");
        exit;
    }

    if (!($return = $stmt->fetchAll()))
    {
        $stmt = null;
        return (true);
    }

    $stmt = null;
    if ($return[0][0] == 1)
        return (true);
    else
        return (false);
}

/**
 * @param string    $name       Username to check
 * @return bool                 True if account is active
 */
function account_active(string $name)
{
    $stmt = DB::prepare("SELECT `account_active` FROM `users` WHERE `username` = :name");
    if (!$stmt->execute(array('name' => $name)))
    {
        $stmt = null;
        print("Error checking if the account is active");
        exit;
    }

    if (!($return = $stmt->fetchAll()))
    {
        $stmt = null;
        return (false);
    }

    $stmt = null;
    if ($return[0][0] == 1)
        return (true);
    else
        return (false);
}

/**
 * @param string    $name       Username to check
 * @return string               Pending email address if exists, NULL if not
 */
function new_email_pending(string $name)
{
    $stmt = DB::prepare("SELECT `new_email` FROM `users` WHERE `username` = :username");
    if ($stmt->execute(array('username' => $name)))
    {
        if (($return = $stmt->fetchAll()))
        {
            $stmt = null;
            return ($return[0][0]);
        }
        else
        {
            $stmt = null;
            return (null);
        }
    }
    else
    {
        $stmt = null;
        print("Error checking if account has a new email pending");
        exit;
    }
}

/**
 * @param string    $resetkey   Key to check if exists
 * @return string               Name of account if key exists, NULL if not
 */
function check_password_reset_key(string $resetkey)
{
    $stmt = DB::prepare("SELECT `username` FROM `users` WHERE `reset_password_key` = :resethash");
    if ($stmt->execute(array('resethash' => $resetkey)))
    {
        if (($return = $stmt->fetchAll()))
        {
            $stmt = null;
            return ($return[0][0]);
        }
        else
        {
            $stmt = null;
            return (null);
        }
    }
    else
    {
        $stmt = null;
        print("Error checking if reset key is correct");
        exit;
    }
}

// ====================== functions that affect data ======================

/**
 * @param string    $name       Username to create
 * @param string    $password   Password to create
 * @param string    $email      Email Address to associate
 * @return void
 */
function create_user(string $name, string $password, string $email)
{
    $emailhash = bin2hex(openssl_random_pseudo_bytes(32));
    $stmt = DB::prepare("INSERT INTO `users` (
        `username`, `password`, `email`, `email_verify`
    ) VALUES (
        :name, :password, :email, :emailhash
    )");

    if (!$stmt->execute(array(
        'name' => $name,
        'password' => hash("whirlpool", $password),
        'email' => $email,
        'emailhash' => $emailhash
    )))
    {
        $stmt = null;
        print("Error creating user");
        exit;
    }
    $stmt = null;
    send_verification_mail($name, $email, $emailhash);
}

/**
 * @param string    $name       Username of account to change
 * @param string    $newpw      New Password
 * @return void
 */
function change_password(string $name, string $newpw)
{
    $stmt = DB::prepare("UPDATE `users` SET `password` = :password WHERE `username` = :username");

    if (!$stmt->execute(array(
        'password' => hash("whirlpool", $newpw),
        'username' => $name
    )))
    {
        $stmt = null;
        print("Error changing password");
        exit;
    }
    $stmt = null;

    $stmt = DB::prepare("UPDATE `users` SET `reset_password_key` = NULL WHERE `username` = :username");
    if (!$stmt->execute(array('username' => $name)))
    {
        $stmt = null;
        print("Error resetting the password reset key");
        exit;
    }
    $stmt = null;
}

/**
 * @param string    $emailhash  Hash to validate an email address.
 *      If the associated account is not active,
 *          it will be set to active and the hash cleared.
 *      If the associated account is already active,
 *          the new email address will be moved to be current and the hash cleared.
 * @return bool                 True if hash was validate, false if hash doesn't exist
 */
function validate_email(string $emailhash)
{
    $stmt = DB::prepare("SELECT `account_active` FROM `users` WHERE `email_verify` = :emailhash");
    if (!$stmt->execute(array('emailhash' => $emailhash)))
    {
        $stmt = null;
        print("Error checking if the validation hash exists");
        exit;
    }

    if (!($return = $stmt->fetchAll()))
    {
        $stmt = null;
        return (false);
    }

    $stmt = null;
    if ($return[0][0] == 0)    // account is not active, this is an activation request
    {
        $stmt = DB::prepare("UPDATE `users`
            SET
                `email_verify` = 'yes',
                `account_active` = true
            WHERE `email_verify` = :emailhash
            ;");
    }
    else        // account is already active, this is a new email validation request
    {
        $stmt = DB::prepare("UPDATE `users`
            SET
                `email_verify` = 'yes',
                `email` = `new_email`,
                `new_email` = NULL
            WHERE `email_verify` = :emailhash
            ;");
    }

    if ($stmt->execute(array('emailhash' => $emailhash)))
    {
        $stmt = null;
        return (true);
    }
    else
    {
        $stmt = null;
        print("Error setting the email as validated");
        exit;
    }
}

/**
 * @param string    $email      Email address to generate and send a reset key to
 * @return bool                 True if email sent successfully, false if email doesn't exist
 */
function send_password_reset_key(string $email)
{
    $resethash = bin2hex(openssl_random_pseudo_bytes(32));
    $stmt = DB::prepare("UPDATE `users`
            SET
                `reset_password_key` = :resethash
            WHERE `email` = :email
            ;");

    if (!$stmt->execute(array('resethash' => $resethash, 'email' => $email)))
    {
        $stmt = null;
        print("Error saving the password reset key");
        exit;
    }

    if ($stmt->rowCount() == 0)
    {
        $stmt = null;
        return (false);
    }
    $stmt = null;
    send_reset_email($email, $resethash);
    return (true);
}

/**
 * @param string    $name       Username to delete
 * @return void
 */
function delete_user(string $name)
{
    $stmt = DB::prepare("DELETE FROM `users` WHERE `username` = :username");

    if (!$stmt->execute(array('username' => $name)))
    {
        $stmt = null;
        print("Error Deleting user from database");
        exit;
    }
    $stmt = null;
}

?>
