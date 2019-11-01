<?php

require_once("../../config/output.php");
require_once("../../config/func_user.php");

session_start();

/**
 * @param string    $username   Username to set session to
 * @return void
 */
function set_session_username(string $username)
{
    $_SESSION["username"] = $username;
}

/**
 * @return void
 */
function logout()
{
    session_destroy();
}

if (!isset($_POST["action"]))
{
    print("No action supplied" . PHP_EOL);
} // don't require login
elseif ($_POST["action"] === "create") // args: username, password, email
{
    if (!$_POST["username"])
        print("No username supplied!" . PHP_EOL);
    elseif (!$_POST["password"])
        print("No password supplied!" . PHP_EOL);
    elseif (!$_POST["email"])
        print("No email supplied!" . PHP_EOL);
    elseif (user_exists($_POST["username"]))
        print("User already exists!" . PHP_EOL);
    elseif (email_used($_POST["email"]))
        print("Email already used!" . PHP_EOL);
    else
    {
        create_user($_POST["username"], $_POST["password"], $_POST["email"]);
        print("Account created successfully" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "validate") // args: hash
{
    if (!$_POST["hash"])
        print("No hash supplied" . PHP_EOL);
    elseif (validate_email($_POST["hash"]))
        print("Email successfully validated!" . PHP_EOL);
    else
        print("Hash doesn't exist!" . PHP_EOL);
}
elseif ($_POST["action"] === "resend") // args: email
{
    if (!$_POST["email"])
        print("No email address supplied" . PHP_EOL);
    elseif (resend_email_validation($_POST["email"]))
        print("Password Reset Email sent successfully!" . PHP_EOL);
    else
        print("Unvalidated email not found!" . PHP_EOL);
}
elseif ($_POST["action"] === "sendreset") // args: email
{
    if (!$_POST["email"])
        print("No email address supplied" . PHP_EOL);
    elseif (send_password_reset_key($_POST["email"]))
        print("Password Reset Email sent successfully!" . PHP_EOL);
    else
        print("Email doesn't exist!" . PHP_EOL);
}
elseif ($_POST["action"] === "resetpw") // args: hash, newpassword
{
    if (!$_POST["hash"])
        print("No hash supplied" . PHP_EOL);
    elseif (!$_POST["newpassword"])
        print("New Password not supplied!" . PHP_EOL);
    elseif ($username = check_password_reset_key($_POST["hash"]))
    {
        change_password($username, $_POST["newpassword"]);
        print("Changed password successfully" . PHP_EOL);
    }
    else
        print("Hash doesn't exist!");
} // require login
elseif ($_POST["action"] === "changepw") // args: oldpassword, newpassword
{
    if (!$_SESSION["username"])
        print("Not logged in" . PHP_EOL);
    elseif (!$_POST["oldpassword"])
        print("Old Password not supplied!" . PHP_EOL);
    elseif (!$_POST["newpassword"])
        print("New Password not supplied!" . PHP_EOL);
    elseif (!correct_pw($_SESSION["username"], $_POST["oldpassword"]))
        print("Incorrect password!" . PHP_EOL);
    else
    {
        change_password($_SESSION["username"], $_POST["newpassword"]);
        print("Changed password successfully" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "changeemail") // args: newemail
{
    if (!$_SESSION["username"])
        print("Not logged in" . PHP_EOL);
    elseif (!$_POST["newemail"])
        print("No email address supplied" . PHP_EOL);
    elseif (email_used($_POST["newemail"]))
        print("Email address already used!" . PHP_EOL);
    else
    {
        change_email($_SESSION["username"], $_POST["newemail"]);
        print("Email Address changed successfully, check your inbox for the verification email" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "changenotify") // args: notify
{
    if (!$_SESSION["username"])
        print("Not logged in" . PHP_EOL);
    elseif (!$_POST["notify"])
        print("No option supplied" . PHP_EOL);
    else
    {
        change_notify($_SESSION["username"], $_POST["notify"] === "true" ? true : false);
        print("Option changed successfully" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "delete") // args: password
{
    if (!$_SESSION["username"])
        print("Not logged in" . PHP_EOL);
    elseif (!$_POST["password"])
        print("No password supplied!" . PHP_EOL);
    elseif (!correct_pw($_SESSION["username"], $_POST["password"]))
        print("Incorrect Password!" . PHP_EOL);
    else
    {
        delete_user($_SESSION["username"]);
        logout();
        print("Account deleted successfully" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "login") // args: username, password
{
    if (!$_POST["username"])
        print("No username supplied!" . PHP_EOL);
    elseif (!$_POST["password"])
        print("No password supplied!" . PHP_EOL);
    elseif (!user_exists($_POST["username"]))
        print("User doesn't exist!" . PHP_EOL);
    elseif (!correct_pw($_POST["username"], $_POST["password"]))
        print("Incorrect password!" . PHP_EOL);
    elseif (!account_active($_POST["username"]))
        print("Account not activated! Please check your emails" . PHP_EOL);
    else
    {
        set_session_username($_POST["username"]);
        print("Logged in Successfully" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "logout")
{
    if (!$_SESSION["username"])
        print("Not logged in" . PHP_EOL);
    else
    {
        logout();
        print("Logged out" . PHP_EOL);
    }
}
else
    print("Invalid action" . PHP_EOL);

?>
