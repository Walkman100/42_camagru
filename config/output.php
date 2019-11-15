<?php

require_once("globals.php");

function output_head(string $title, string $additional_head = null)
{
    global $ROOT_PATH;

    //set headers to NOT cache a page
    //header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
    header("Cache-Control: private, no-cache, no-store, proxy-revalidate, no-transform");
    header("Pragma: no-cache"); //HTTP 1.0
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

    print("<!DOCTYPE html><html lang='en'>
      <head><meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv='Cache-control' content='no-store'>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'>
        <title>" . $title . "</title>
        <link rel='stylesheet' href='" . $ROOT_PATH . "include/main.css' type='text/css'>
        <script type='text/javascript' src='" . $ROOT_PATH . "include/main.js'></script>");
    if ($additional_head !== null)
        print($additional_head);
    print("</head><body>");
}

function output_end()
{
    print("</body></html>" . PHP_EOL);
}


function output_header()
{
    global $ROOT_PATH;

    print("<div class='toolbar'><div class='toolbarsub'>");
    print("<a href=\"" . $ROOT_PATH . "posts\"><div class='toolbarbutton'>Posts</div></a>");
    if (isset($_SESSION['username']))
    {
        print(" <a href=\"" . $ROOT_PATH . "post\"><div class='toolbarbutton'>Add</div></a>");
        print(" <div class='username'>" . $_SESSION['username'] . "</div>");
        print(" <a href=\"" . $ROOT_PATH . "logout\"><div class='toolbarbutton'>Logout</div></a>");
        print(" <a href=\"" . $ROOT_PATH . "profile\"><div class='toolbarbutton'>Profile</div></a>");
    }
    else
        print("<a href=\"" . $ROOT_PATH . "login\"><div class='toolbarbutton'>Login</div></a>");
    print("</div></div>");
    print("<div class='main'>");
}

function output_footer()
{
    print("</div>");
}


/**
 * Prints the connecting to the database error and quits the current PHP execution
 * @param string    $message        Message to print.
 * @return void
 */
function dbconnectfailed(string $message)
{
    http_response_code(503); // Service Unavailable
    output_head("Database Error");
    print("<h1>Error Connecting to the database!</h1>");
    print("Error message: " . $message);
    output_end();
    exit;
    die;
}

/**
 * Outputs the error message followed by <br /> and PHP_EOL.
 * Also sends the response code if provided.
 *
 * @param string    $message        Message to print.
 * @param integer   $response_code  Response code:
 *      400: 'Bad Request'
 *      401: 'Unauthorized'
 *      403: 'Forbidden'
 *      413: 'Request Entity Too Large'
 *      414: 'Request-URI Too Large'
 *      415: 'Unsupported Media Type'
 *      500: 'Internal Server Error'
 *      501: 'Not Implemented'
 * See https://www.php.net/manual/en/function.http-response-code.php
 * @return void
 */
function output_error(string $message, int $response_code = null)
{
    if (!is_null($response_code))
        http_response_code($response_code);
    print($message . PHP_EOL);
}

?>
