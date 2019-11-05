<?php

function output_head(string $title, string $additional_head = null)
{
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
        <link rel='stylesheet' href='/include/main.css' type='text/css'>");
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
    print("<div class='toolbar'><div class='toolbarsub'>");
    print("<a href=\"/posts\"><div class='toolbarbutton'>Posts</div></a>");
    if (isset($_SESSION['username']))
    {
        print(" <a href=\"/post\"><div class='toolbarbutton'>Add</div></a>");
        print(" <div class='username'>" . $_SESSION['username'] . "</div>");
        print(" <a href=\"/logout\"><div class='toolbarbutton'>Logout</div></a>");
        print(" <a href=\"/profile\"><div class='toolbarbutton'>Profile</div></a>");
    }
    else
        print("<a href=\"/login\"><div class='toolbarbutton'>Login</div></a>");
    print("</div></div>");
    print("<div>");
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
    print($message . "<br />" . PHP_EOL);
}

?>
