<?php

require_once("setup.php");

date_default_timezone_set("Africa/Johannesburg");
if ($_SERVER['DOCUMENT_ROOT'])
    $server_root = $_SERVER['DOCUMENT_ROOT'];
else
    $server_root = "/Volumes/wtc-mcarter/camagru";

/**
 * @param string    $origmd5    md5 of image to copy
 * @param string    $name       Username uploading image
 * @return bool                 True if post was added successfully, false if source image doesn't exist, user can't be found, or copy failed
 */
function add_post(string $origmd5, string $name)
{
    $currdate = date("Y-m-d H:i:s");

    global $server_root;
    if (!file_exists($server_root . "/userdata/" . $origmd5 . ".png"))
        return (false);

    $stmt = DB::prepare("SELECT `id` FROM `users` WHERE `username` = :name");
    if (!$stmt->execute(array('name' => $name)))
    {
        $stmt = null;
        print("Error getting user id");
        exit;
    }
    if (!($return = $stmt->fetchAll()))
    {
        $stmt = null;
        return (false);
    }
    $stmt = null;
    $userid = intval($return[0][0]);

    $stmt = DB::prepare("INSERT INTO `posts` (
            `user_id`, `post_date`, `liked_user_ids`
        ) VALUES (
            :userid, :postdate, :liked_user_ids
        )");
    if (!$stmt->execute(array(
            'userid' => $userid,
            'postdate' => $currdate,
            'liked_user_ids' => serialize(array())
            )))
    {
        $stmt = null;
        print("Error creating post");
        exit;
    }

    return (copy(  realpath($server_root . "/userdata/" . $origmd5 . ".png"),
                            $server_root . "/postimages/" . DB::lastInsertID() . ".png"));
}

/**
 * @param int       $postid     ID of post and image to delete
 * @return void
 */
function delete_post(int $postid)
{
    $stmt = DB::prepare("DELETE FROM `posts` WHERE `post_id` = :postid");

    if (!$stmt->execute(array('postid' => $postid)))
    {
        $stmt = null;
        print("Error deleting post from database");
        exit;
    }
    $stmt = null;

    global $server_root;
    unlink(realpath($server_root . "/postimages/" . $postid . ".png"));
}

/**
 * @param int       $postid     ID of post to like
 * @param string    $name       Username to like post
 * @param bool      $like       True to like post, False to unlike
 * @return bool                 True if post's likes was modified successfully, false if user can't be found or post doesn't exist
 */
function like_post(int $postid, string $name, bool $like = true)
{
    $stmt = DB::prepare("SELECT `id` FROM `users` WHERE `username` = :name");
    if (!$stmt->execute(array('name' => $name)))
    {
        $stmt = null;
        print("Error getting user id");
        exit;
    }
    if (!($return = $stmt->fetchAll()))
    {
        $stmt = null;
        return (false);
    }
    $userid = intval($return[0][0]);

    $stmt = DB::prepare("SELECT `liked_user_ids` FROM `posts` WHERE `post_id` = :postid");
    if (!$stmt->execute(array('postid' => $postid)))
    {
        $stmt = null;
        print("Error getting likes");
        exit;
    }
    if (!$return = $stmt->fetchAll())
    {
        $stmt = null;
        return (false);
    }

    $like_array = unserialize($return[0][0]);
    if ($like)
        $like_array[$userid] = 1;
    else
        unset($like_array[$userid]);

    $stmt = DB::prepare("UPDATE `posts` SET `liked_user_ids` = :likearr WHERE `post_id` = :postid");
    if (!$stmt->execute(array(
        'likearr' => serialize($like_array),
        'postid' => $postid
    )))
    {
        $stmt = null;
        print("Error updating liked values");
        exit;
    }
    $stmt = null;
    return (true);
}

?>
