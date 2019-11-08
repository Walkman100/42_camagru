<?php

require_once('../config/output.php');

session_start();
session_destroy();
session_start(); // make sure old session is overwritten
session_destroy();

output_head('Logout');

output_header();

print('<br /><br />');

print('Logged Out');

output_footer();

output_end();

?>
