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
        print("<div class='username'>" . $_SESSION['username'] . "</div>");
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


function dbconnectfailed(string $message)
{
    output_head("Database Error");
    print("<h1>Error Connecting to the database!</h1>");
    print("Error message: " . $message);
    output_end();
    exit;
    die;
}

?>
