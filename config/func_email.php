<?php

require_once("globals.php");

function send_mail(string $to, string $subject, string $htmltext)
{
    global $DOMAIN_NAME;
    $headers = array(
        'From' => 'Camagru <noreply@' . $DOMAIN_NAME . '>',
        'Reply-To' => 'Camagru <noreply@' . $DOMAIN_NAME . '>',
        'Date' => date('r'),
        'X-Mailer' => 'PHP/' . phpversion(),
        'Message-ID' => '<' . sha1(microtime(true)) . '@' . $DOMAIN_NAME . '>',
        'MIME-Version' => '1.0',
        'Content-Type' => 'text/html;charset=UTF-8'
    );

    mail($to, $subject, $htmltext, $headers);
}

function send_verification_mail($name, $email, $emailhash)
{
    global $DOMAIN_NAME;
    $verifypage = "<a href='http://" . $DOMAIN_NAME . "/verify?hash=$emailhash'>Verify Address</a>";

    $message = "<html><head><title>Email Verification</title></head><body>
        <p>Hello $name. To verify this address, go to the following page: $verifypage</p>
        <p>If this was not you, ignore this email and the address won't be used.</p>
        </body></html>
    ";

    send_mail($email, "Camagru Account Verification", $message);
}

function send_reset_email($email, $resethash)
{
    global $DOMAIN_NAME;
    $resetpage = "<a href='http://" . $DOMAIN_NAME . "/reset?hash=$resethash'>Verify Address</a>";

    $message = "<html><head><title>Password Reset</title></head><body>
        <p>A password reset was requested for an account with this address.</p>
        <p>To continue the reset process, go to the following page: $resetpage</p>
        </body></html>
    ";

    send_mail($email, "Camagru Password Reset", $message);
}

function send_comment_notification($name, $email, $commenttext)
{
    $commenttext = wordwrap($commenttext, 80, "<br \>");

    $message = "<html><head><title>Post Notification</title></head><body>
        <p>Hello $name, someone commented on your post:</p>
        <p>$commenttext</p>
        </body></html>
    ";

    send_mail($email, "Someone Commented on your post", $message);
}

?>
