<?php

require_once("database.php");
require_once("globals.php");
require_once("output.php");
require_once("func_images.php");

function setup_db()
{
    global $server_root;

    print("Connecting to the db server...");
    $tmpPDO = new PDO($GLOBALS["DB_DSN"], $GLOBALS["DB_USER"], $GLOBALS["DB_PASSWORD"], array(PDO::ATTR_PERSISTENT => true));
    $tmpPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    print(" Complete.<br />" . PHP_EOL . "Creating db...");
    $tmpPDO->exec("CREATE DATABASE IF NOT EXISTS `" . $GLOBALS["DB_NAME"] . "`;");
    $tmpPDO->exec("USE `" . $GLOBALS["DB_NAME"] . "`;");

    print(" Complete.<br />" . PHP_EOL . "Creating user table...");
    // PRIMARYKEY automatically adds: UNIQUE and NOT NULL
    $tmpPDO->exec("CREATE TABLE IF NOT EXISTS `users` (
            `id`                    INT         PRIMARY KEY AUTO_INCREMENT,
            `username`              TEXT        NOT NULL,
            `password`              TEXT        NOT NULL,
            `email`                 TEXT        NOT NULL,
            `notify`                BOOLEAN     NOT NULL DEFAULT TRUE,
            `account_active`        BOOLEAN     NOT NULL DEFAULT FALSE,
            `email_verify`          TEXT        NOT NULL,
            `new_email`             TEXT DEFAULT    NULL,
            `reset_password_key`    TEXT DEFAULT    NULL
        );");

    print(" Complete.<br />" . PHP_EOL . "Creating posts table...");
    $tmpPDO->exec("CREATE TABLE IF NOT EXISTS `posts` (
            `post_id`               INT         PRIMARY KEY AUTO_INCREMENT,
            `user_id`               INT         NOT NULL,
            `post_date`             DATETIME    NOT NULL,
            `liked_user_ids`        TEXT        NOT NULL
        );");

    print(" Complete.<br />" . PHP_EOL . "Creating comments table...");
    $tmpPDO->exec("CREATE TABLE IF NOT EXISTS `comments` (
            `post_id`               INT         NOT NULL,
            `user_id`               INT         NOT NULL,
            `post_date`             DATETIME    NOT NULL,
            `text`                  TEXT        NOT NULL
        );");

    print(" Complete.<br />" . PHP_EOL . "Creating savedimages table...");
    $tmpPDO->exec("CREATE TABLE IF NOT EXISTS `savedimages` (
            `user_id`               INT         NOT NULL,
            `upload_date`           DATETIME    NOT NULL,
            `md5`                   VARCHAR(32) PRIMARY KEY
        );");

    print(" Complete.<br />" . PHP_EOL . "Creating overlays table...");
    $tmpPDO->exec("CREATE TABLE IF NOT EXISTS `overlays` (
            `image_id`              INT         PRIMARY KEY AUTO_INCREMENT
        );");

    print(" Complete.<br />" . PHP_EOL . "Closing connection...");
    $tmpPDO = null;

    print(" Complete.<br />" . PHP_EOL . "Creating image folders...");
    mkdir($server_root . "/overlays/", 0777, true);
    mkdir($server_root . "/userdata/", 0777, true);
    mkdir($server_root . "/postimages/", 0777, true);

    print(" Complete. <br />" . PHP_EOL . "Indexing all overlays...");
    add_all_overlays();

    print(" Complete.<br />" . PHP_EOL);
}

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
