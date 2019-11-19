<?php

require_once('../config/globals.php');
require_once('../config/output.php');

session_start();

output_head('Camagru');

output_header();

print("<br /><br />");
print("<div class='form'>");
    print("<h2>Welcome</h2>");
        print("<a href='" . $ROOT_PATH . "posts.php'>Posts</a><br />");

    if (isset($_SESSION["username"])) {
        print("<br /><a href='" . $ROOT_PATH . "logout.php'>Logout</a><br />");
        print("<br /><a href='" . $ROOT_PATH . "post.php'>Create Post</a><br />");
        print("<br /><a href='" . $ROOT_PATH . "profile.php'>Profile</a><br />");
    } else {
        print("<br /><a href='" . $ROOT_PATH . "login.php'>Login</a><br />");
        print("<br /><a href='" . $ROOT_PATH . "create.php'>Create Account</a><br />");
    }

    print("<br /><a href='" . $ROOT_PATH . "reset.php'>Reset Password</a><br />");
    print("<br /><a href='" . $ROOT_PATH . "resend.php'>Resend Verification Email</a>");
print("</div>"); // form

output_footer();

output_end();

?>
