<?php

require_once("../../config/output.php");
require_once("../../config/func_posts.php");
require_once("../../config/func_images.php");

session_start();

if (!isset($_POST["action"]))
{
    output_error("No action supplied", 400);
}
elseif ($_POST["action"] === "add") // args: md5
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    elseif ($_SESSION["username"] === $ADMIN_USER)
        output_error("Cannot post as Administrator Account", 403);
    elseif (!$_POST["md5"])
        output_error("No md5 supplied!", 400);
    elseif (add_post($_POST["md5"], $_SESSION["username"]))
        print("Post added successfully!" . PHP_EOL);
    else
        output_error("Failed to add post!", 400);
}
elseif ($_POST["action"] === "delete") // args: postid
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    elseif (!$_POST["postid"])
        output_error("No postid supplied!", 400);
    elseif (!post_is_owned($_POST["postid"], $_SESSION["username"]) && $_SESSION["username"] !== $ADMIN_USER)
        output_error("Logged in user does not own post!", 403);
    else
    {
        delete_post($_POST["postid"]);
        print("Post deleted successfully" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "deleteimage") // args: md5
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    elseif (!$_POST["md5"])
        output_error("No md5 supplied!", 400);
    elseif (!image_is_owned($_POST["md5"], $_SESSION["username"]) && $_SESSION["username"] !== $ADMIN_USER)
        output_error("Logged in user does not own image!", 403);
    else
    {
        delete_image($_POST["md5"]);
        print("Image deleted successfully!" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "deleteoverlay") // args: id
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    elseif (!$_POST["id"])
        output_error("No id supplied!", 400);
    elseif ($_SESSION["username"] !== $ADMIN_USER)
        output_error("Not logged in as admin!", 403);
    else
    {
        delete_overlay($_POST["id"]);
        print("Overlay deleted" . PHP_EOL);
    }
}
elseif ($_POST["action"] === "like") // args: postid, like (true|false)
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    elseif ($_SESSION["username"] === $ADMIN_USER)
        output_error("Cannot like as Administrator Account", 403);
    elseif (!$_POST["postid"])
        output_error("No postid supplied!", 400);
    elseif (!$_POST["like"])
        output_error("Option not specified!", 400);
    elseif (like_post($_POST["postid"], $_SESSION["username"], $_POST["like"] === "true" ? true : false))
        print("Post like status changed successfully");
    else
        output_error("Failed to change post like status", 400);
}
elseif ($_POST["action"] === "isliked") // args: postid
{
    if (!isset($_SESSION["username"]))
        output_error("Not logged in", 401);
    elseif (!$_POST["postid"])
        output_error("No postid supplied!", 400);
    elseif (is_liked($_POST["postid"], $_SESSION["username"]))
        print("true");
    else
        print("false");
}
elseif ($_POST["action"] === "likecount") // args: postid
{
    if (!$_POST["postid"])
        output_error("No postid supplied!", 400);
    else
        print(likes_count($_POST["postid"]));
}
else
    output_error("Invalid action", 400);

?>
