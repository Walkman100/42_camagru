<?php

function send_verification_mail($name, $email, $emailhash)
{
    print("Verification email sent to $email ($name) with hash $emailhash <br />" . PHP_EOL);
}

function send_reset_email($email, $resethash)
{
    print("Password reset email sent to $email with hash $resethash <br />" . PHP_EOL);
}

?>
