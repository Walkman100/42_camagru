<?php

date_default_timezone_set("Africa/Johannesburg");

// fallback for physical location
$COMMANDLINE_ROOT = "/Volumes/wtc-mcarter/camagru/site";

if ($_SERVER['DOCUMENT_ROOT'])
    $server_root = $_SERVER['DOCUMENT_ROOT'];
else
    $server_root = $COMMANDLINE_ROOT;

// default domain name, used in emails
$DOMAIN_NAME = "cmg.carteronline.net";

// php POST max size is 40M, so set image max size to 35M
$MAX_UPLOAD_SIZE = 35000000;

?>
