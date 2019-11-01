<?php

require_once("../../config/output.php");
require_once("../../config/func_comments.php");

session_start();

if (!isset($_POST["action"]))
{
    print("No action supplied" . PHP_EOL);
}
elseif ($_POST["action"] === "add") // args: postid, posttext
{
    if (!$_SESSION["username"])
        print("Not logged in" . PHP_EOL);
    elseif (!$_POST["postid"])
        print("No postid supplied!" . PHP_EOL);
    elseif (!$_POST["posttext"])
        print("No comment text supplied!" . PHP_EOL);     // php automatically urldecodes $_POST values
    elseif (post_comment($_POST["postid"], $_SESSION["username"], $_POST["posttext"]))
        print("Comment added successfully!" . PHP_EOL);
    else
        print("Failed to add comment!" . PHP_EOL);
}
else
    print("Invalid action" . PHP_EOL);

?>
