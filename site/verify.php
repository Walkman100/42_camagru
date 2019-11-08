<?php

require_once('../config/output.php');
require_once('../config/func_user.php');

session_start();

output_head('Verify Email Address');

output_header();

print('<br /><br />');

if (!isset($_GET['hash']))
    print('No hash supplied' . PHP_EOL);
elseif (validate_email($_GET['hash']))
    print('Email successfully validated!' . PHP_EOL);
else
    print("Hash doesn't exist!" . PHP_EOL);

output_footer();

output_end();

?>
