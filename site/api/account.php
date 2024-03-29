<?php

require_once("../../config/output.php");
require_once("../../config/globals.php");
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
    output_error("No action supplied", 400);
} // don't require login
elseif ($_POST["action"] === "create") // args: username, password, email
{
    if (!isset($_POST["username"]))
        output_error("No username supplied!", 400);
    elseif (!isset($_POST["password"]))
        output_error("No password supplied!", 400);
    elseif (!isset($_POST["email"]))
        output_error("No email supplied!", 400);
    elseif ($_POST["username"] === $ADMIN_USER)
        output_error("Cannot create an account with the same username as the Administrator Account", 403);
    elseif (user_exists($_POST["username"]))
        output_error("User already exists!", 400);
    elseif (!preg_match("/" . $USERNAME_REGEX . "/", $_POST["username"]))
        output_error("Username must consist of " . $USERNAME_HINT, 400);
    elseif (!preg_match("/" . $PASSWORD_REGEX . "/", $_POST["password"]))
        output_error("Password must contain " . $PASSWORD_HINT, 400);
    elseif (email_used($_POST["email"]))
        output_error("Email address already used!", 400);
    elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
        output_error("Invalid email address!", 400);
    elseif (!checkdnsrr( substr($_POST["email"], strrpos($_POST["email"], '@') + 1) , 'MX'))
        output_error("Invalid email address domain!", 400);
    else
    {
        create_user($_POST["username"], $_POST["password"], $_POST["email"]);
        print("Account created successfully, check your inbox for the verification email" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "verify") // args: hash
{
    if (!isset($_POST["hash"]))
        output_error("No hash supplied", 400);
    elseif (verify_email($_POST["hash"]))
        print("Email successfully verified!" . PHP_EOL);
    else
        output_error("Hash doesn't exist!", 400);
}
elseif ($_POST["action"] === "resend") // args: email
{
    if (!isset($_POST["email"]))
        output_error("No email address supplied", 400);
    elseif (resend_email_verification($_POST["email"]))
        print("Verification Email sent successfully!" . PHP_EOL);
    else
        output_error("Unverified email not found!", 400);
}
elseif ($_POST["action"] === "sendreset") // args: email
{
    if (!isset($_POST["email"]))
        output_error("No email address supplied", 400);
    elseif (send_password_reset_key($_POST["email"]))
        print("Password Reset Email sent successfully!" . PHP_EOL);
    else
        output_error("Email doesn't exist!", 400);
}
elseif ($_POST["action"] === "resetpw") // args: hash, newpassword
{
    if (!isset($_POST["hash"]))
        output_error("No hash supplied", 400);
    elseif (!isset($_POST["newpassword"]))
        output_error("New Password not supplied!", 400);
    elseif (!preg_match("/" . $PASSWORD_REGEX . "/", $_POST["newpassword"]))
        output_error("Password must contain " . $PASSWORD_HINT, 400);
    elseif ($username = check_password_reset_key($_POST["hash"]))
    {
        change_password($username, $_POST["newpassword"]);
        print("Changed password successfully" . PHP_EOL);
    }
    else
        output_error("Hash doesn't exist!", 400);
} // require login
elseif ($_POST["action"] === "changeusername") // args: username
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    elseif ($_SESSION["username"] === $ADMIN_USER)
        output_error("Cannot change settings for Administrator Account", 403);
    elseif (!isset($_POST["username"]))
        output_error("No username supplied!", 400);
    elseif ($_POST["username"] === $ADMIN_USER)
        output_error("Cannot change username to the same username as the Administrator Account", 403);
    elseif (!preg_match("/" . $USERNAME_REGEX . "/", $_POST["username"]))
        output_error("Username must consist of " . $USERNAME_HINT, 400);
    elseif (user_exists($_POST["username"]))
        output_error("Username in use!", 400);
    else
    {
        change_username($_SESSION["username"], $_POST["username"]);
        $_SESSION["username"] = $_POST["username"];
        print("Username changed successfully" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "changepw") // args: oldpassword, newpassword
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    elseif ($_SESSION["username"] === $ADMIN_USER)
        output_error("Cannot change settings for Administrator Account", 403);
    elseif (!isset($_POST["oldpassword"]))
        output_error("Old Password not supplied!", 400);
    elseif (!isset($_POST["newpassword"]))
        output_error("New Password not supplied!", 400);
    elseif (!correct_pw($_SESSION["username"], $_POST["oldpassword"]))
        output_error("Incorrect password!", 400);
    elseif (!preg_match("/" . $PASSWORD_REGEX . "/", $_POST["newpassword"]))
        output_error("Password must contain " . $PASSWORD_HINT, 400);
    else
    {
        change_password($_SESSION["username"], $_POST["newpassword"]);
        print("Changed password successfully" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "changeemail") // args: newemail
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    elseif ($_SESSION["username"] === $ADMIN_USER)
        output_error("Cannot change settings for Administrator Account", 403);
    elseif (!isset($_POST["newemail"]))
        output_error("No email address supplied", 400);
    elseif (email_used($_POST["newemail"]))
        output_error("Email address already used!", 400);
    elseif (!filter_var($_POST["newemail"], FILTER_VALIDATE_EMAIL))
        output_error("Invalid email address!", 400);
    elseif (!checkdnsrr( substr($_POST["newemail"], strrpos($_POST["newemail"], '@') + 1) , 'MX'))
        output_error("Invalid domain!", 400);
    else
    {
        change_email($_SESSION["username"], $_POST["newemail"]);
        print("Email Address changed successfully, check your inbox for the verification email" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "changenotify") // args: notify
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    elseif ($_SESSION["username"] === $ADMIN_USER)
        output_error("Cannot change settings for Administrator Account", 403);
    elseif (!isset($_POST["notify"]))
        output_error("No option supplied", 400);
    else
    {
        change_notify($_SESSION["username"], $_POST["notify"] === "true" ? true : false);
        print("Option changed successfully" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "delete") // args: password
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    elseif ($_SESSION["username"] === $ADMIN_USER)
        output_error("Cannot change settings for Administrator Account", 403);
    elseif (!isset($_POST["password"]))
        output_error("No password supplied!", 400);
    elseif (!correct_pw($_SESSION["username"], $_POST["password"]))
        output_error("Incorrect Password!", 400);
    else
    {
        delete_user($_SESSION["username"]);
        logout();
        print("Account deleted successfully" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "login") // args: username, password
{
    if (!isset($_POST["username"]))
        output_error("No username supplied!", 400);
    elseif (!isset($_POST["password"]))
        output_error("No password supplied!", 400);
    elseif ($_POST["username"] === $ADMIN_USER && $_POST["password"] === $ADMIN_PASSWORD)
    {
        set_session_username($_POST["username"]);
        print("Logged in as Administrator" . PHP_EOL);
    }
    elseif (!user_exists($_POST["username"]))
        output_error("User doesn't exist!", 400);
    elseif (!correct_pw($_POST["username"], $_POST["password"]))
        output_error("Incorrect password!", 400);
    elseif (!account_active($_POST["username"]))
        output_error("Account not activated! Please check your emails", 400);
    else
    {
        set_session_username($_POST["username"]);
        print("Logged in Successfully" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "logout")
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    else
    {
        logout();
        print("Logged out" . PHP_EOL);
    }
}
else
    output_error("Invalid action", 400);

?>
