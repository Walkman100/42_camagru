<?php

require_once("../../config/setup.php");
require_once("../../config/output.php");
require_once("../../config/func_user.php");
require_once("../../config/func_posts.php");
require_once("../../config/func_comments.php");
require_once("../../config/func_images.php");
require_once("../../config/func_email.php");

session_start();

if (!isset($_POST["action"]))
{
    print("No action supplied" . PHP_EOL);
}
elseif ($_POST["action"] === "add") // args: md5
{
    if (!$_SESSION["username"])
        print("Not logged in" . PHP_EOL);
    elseif (!$_POST["md5"])
        print("No md5 supplied!" . PHP_EOL);
    elseif (add_post($_POST["md5"], $_SESSION["username"]))
        print("Post added successfully!" . PHP_EOL);
    else
        print("Failed to add post!" . PHP_EOL);
}
elseif ($_POST["action"] === "delete") // args: postid
{
    if (!$_SESSION["username"])
        print("Not logged in" . PHP_EOL);
    elseif (!$_POST["postid"])
        print("No postid supplied!" . PHP_EOL);
    elseif (!post_is_owned($_POST["postid"], $_SESSION["username"]))
        print("Logged in user does not own post!" . PHP_EOL);
    else
    {
        delete_post($_POST["postid"]);
        print("Post deleted successfully" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "like") // args: postid, like (true|false)
{
    if (!$_SESSION["username"])
        print("Not logged in" . PHP_EOL);
    elseif (!$_POST["postid"])
        print("No postid supplied!" . PHP_EOL);
    elseif (!$_POST["like"])
        print("Option not specified!");
    elseif (like_post($_POST["postid"], $_SESSION["username"], $_POST["like"] === "true" ? true : false))
        print("Post like status changed successfully");
    else
        print("Failed to change post like status");
}
elseif ($_POST["action"] === "isliked") // args: postid
{
    if (!$_SESSION["username"])
        print("Not logged in" . PHP_EOL);
    elseif (!$_POST["postid"])
        print("No postid supplied!" . PHP_EOL);
    elseif (is_liked($_POST["postid"], $_SESSION["username"]))
        print("true");
    else
        print("false");
}
elseif ($_POST["action"] === "likecount") // args: postid
{
    if (!$_POST["postid"])
        print("No postid supplied!" . PHP_EOL);
    else
        print(likes_count($_POST["postid"]));
}
else
    print("Invalid action" . PHP_EOL);

?>
