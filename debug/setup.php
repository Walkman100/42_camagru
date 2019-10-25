<?php

include_once("../config/setup.php");
include_once("../config/output.php");

output_head("Database Setup");
print("Starting DB setup...<br />" . PHP_EOL);

try
{
    setup_db();
}
catch (PDOException $e)
{
    print("<h1>Error setting up the database!</h1>");
    print("Error message: " . $e->getMessage());
    output_end();
    exit;
    die;
}

print("DB Setup Successful!<br />" . PHP_EOL);
output_end();

?>
