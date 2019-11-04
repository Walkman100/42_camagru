<?php

require_once("database.php");
require_once("output.php");

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
