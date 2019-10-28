<?php

require_once("database.php");
require_once("output.php");

function setup_db()
{
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
            `image_id`              INT         PRIMARY KEY AUTO_INCREMENT,
            `user_id`               INT         NOT NULL,
            `upload_date`           DATETIME    NOT NULL,
            `md5`                   VARCHAR(32) NOT NULL
        );");

    print(" Complete.<br />" . PHP_EOL . "Creating overlays table...");
    $tmpPDO->exec("CREATE TABLE IF NOT EXISTS `overlays` (
            `image_id`              INT     PRIMARY KEY
        );");
    // TODO: add all images in overlays/*.png to table

    print(" Complete.<br />" . PHP_EOL . "Closing connection...");
    $tmpPDO = null;
    print(" Complete.<br />" . PHP_EOL);
}

/**
 * @method \PDOStatement|bool prepare(string $querystring) Prepares a statement for execution and returns a statement object
 * @method int|bool exec(string $querystring) Execute an SQL statement and return the number of affected rows
 * @method string lastInsertID Returns the ID of the last inserted row or sequence value
 */
class DB
{
    private static $objInstance;

    /*
     * Class Constructor - Create a new database connection if one doesn't exist
     * Set to private so no-one can create a new instance via ' = new DB();'
     */
    private function __construct() {}

    /*
     * Like the constructor, we make __clone private so nobody can clone the instance
     */
    private function __clone() {}

    /**
     * Returns DB instance or create initial connection
     * @return $objInstance;
     */
    public static function getInstance()
    {
        if (!self::$objInstance)
        {
            try
            {
                self::$objInstance = new PDO($GLOBALS["DB_DSN_FULL"], $GLOBALS["DB_USER"], $GLOBALS["DB_PASSWORD"], array(PDO::ATTR_PERSISTENT => true));
                self::$objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $e)
            {
                dbconnectfailed($e->getMessage());
            }
        }
        return self::$objInstance;
    }

    /**
     * Passes on any static calls to this class onto the singleton PDO instance
     * @param $chrMethod, $arrArguments
     * @return $mix
     */
    final public static function __callStatic($chrMethod, $arrArguments)
    {
        $objInstance = self::getInstance();
        return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);
    }
}

?>
