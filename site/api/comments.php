<?php

require_once("../../config/output.php");
require_once("../../config/func_comments.php");

session_start();

if (!isset($_POST["action"]))
{
    output_error("No action supplied", 400);
}
elseif ($_POST["action"] === "add") // args: postid, posttext
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    elseif (!$_POST["postid"])
        output_error("No postid supplied!", 400);
    elseif (!$_POST["posttext"])
        output_error("No comment text supplied!", 400);     // php automatically urldecodes $_POST values
    elseif (post_comment($_POST["postid"], $_SESSION["username"], $_POST["posttext"]))
        print("Comment added successfully!" . PHP_EOL);
    else
        output_error("Failed to add comment!", 400);
}
else
    output_error("Invalid action", 400);

?>
