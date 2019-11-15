<?php

date_default_timezone_set("Africa/Johannesburg");

$ADMIN_USER = "admin";
$ADMIN_PASSWORD = "admin";

// URL after domain name - used for all links
if (strstr($_SERVER['SCRIPT_NAME'], '/site/'))
    $ROOT_PATH = strstr($_SERVER['SCRIPT_NAME'], '/site/', true) . '/site/';
else        // SCRIPT_NAME is the URL to the executed script, so we strip everything after /site/ then append /site/
    $ROOT_PATH = '/';

// $server_root is used for image locations
if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] !== '')
    $server_root = $_SERVER['DOCUMENT_ROOT'] . rtrim($ROOT_PATH, '/');
else
    $server_root = $_SERVER['PWD']; // get the working directory when using command line php

// default domain name, used in emails
if (isset($_SERVER["SERVER_NAME"]) && isset($_SERVER["SERVER_PORT"]))
    $DOMAIN_NAME = $_SERVER["SERVER_NAME"] . ':' . $_SERVER["SERVER_PORT"];
else
    $DOMAIN_NAME = $_SERVER['PWD']; // get the working directory when using command line php

// username validation for browser and server
$USERNAME_REGEX = "^[a-zA-Z][a-zA-Z0-9-_\. ]{4,20}$";
$USERNAME_HINT = "only letters, numbers or (-_\. ), and minimum 5 & maximum 20 characters";

// password validation for browser and server
$PASSWORD_REGEX = "^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$";
$PASSWORD_HINT = "at least one number, lowercase and uppercase letter, minimum 8 characters";

// php POST max size is 40M, so set image max size to 35M
$MAX_UPLOAD_SIZE = 35000000;

?>
