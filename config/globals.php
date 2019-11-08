<?php

date_default_timezone_set("Africa/Johannesburg");

// fallback for physical location
$COMMANDLINE_ROOT = "/Volumes/wtc-mcarter/camagru/site";

$ADMIN_USER = "admin";
$ADMIN_PASSWORD = "admin";

if ($_SERVER['DOCUMENT_ROOT'])
    $server_root = $_SERVER['DOCUMENT_ROOT'];
else
    $server_root = $COMMANDLINE_ROOT;

// default domain name, used in emails
$DOMAIN_NAME = "cmg.carteronline.net";

// username validation for browser and server
$USERNAME_REGEX = "^[a-zA-Z][a-zA-Z0-9-_\. ]{4,20}$";
$USERNAME_HINT = "only letters, numbers or (-_\. ), and minimum 5 & maximum 20 characters";

// password validation for browser and server
$PASSWORD_REGEX = "^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$";
$PASSWORD_HINT = "at least one number, lowercase and uppercase letter, minimum 8 characters";

// php POST max size is 40M, so set image max size to 35M
$MAX_UPLOAD_SIZE = 35000000;

?>
