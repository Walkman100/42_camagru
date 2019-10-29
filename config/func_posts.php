<?php

require_once("setup.php");
require_once("func_user.php");
require_once("func_comments.php");

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

    if (!$userid = get_user_id($name))
        return (false);

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
    delete_comments($postid);

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
    if (!$userid = get_user_id($name))
        return (false);

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

/**
 * @param int       $postid     ID of post to check
 * @param string    $name       Username to check
 * @return bool                 True if post is liked, false if post is not liked, user doesn't exist, or post doesn't exist
 */
function is_liked(int $postid, string $name)
{
    if (!$userid = get_user_id($name))
        return (false);

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
    return (isset($like_array[$userid]));
}

/**
 * @param int       $postid     ID of post to check
 * @return int                  Count of likes - 0 if post doesn't exist
 */
function likes_count(int $postid)
{
    $stmt = DB::prepare("SELECT `liked_user_ids` FROM `posts` WHERE `post_id` = :postid");
    if (!$stmt->execute(array('postid' => $postid)))
    {
        $stmt = null;
        print("Error getting like count");
        exit;
    }
    if (!$return = $stmt->fetchAll())
    {
        $stmt = null;
        return (0);
    }

    $like_array = unserialize($return[0][0]);
    return (count($like_array));
}

/**
 * @param int       $pageindex  Index of page of posts to get (1-based, first page = 1, second page = 2)
 * @return array|null           Index-based array of posts, each consisting of an Associative array with [post_id], [username], and [post_date] elements
 */
function get_posts(int $pageindex)
{
    $stmt = DB::prepare("SELECT
            `post_id`,
            `users`.`username`,
            `post_date`
        FROM
            `posts`
        INNER JOIN `users` ON `posts`.`user_id` = `users`.`id`
        ORDER BY
            `post_date` DESC,
            `post_id` DESC
        LIMIT
            :limit1, :limit2
        ;");

    $stmt->bindValue(':limit1', intval(($pageindex - 1) * 5), PDO::PARAM_INT);
    $stmt->bindValue(':limit2', 5, PDO::PARAM_INT);
    if (!$stmt->execute())
    {
        $stmt = null;
        print("Error getting posts");
        exit;
    }
    if (!$return = $stmt->fetchAll(PDO::FETCH_ASSOC))
    {
        $stmt = null;
        return (null);
    }
    return ($return);
}

/**
 * @return int                  Amount of pages needed to display all posts
 */
function post_page_count()
{
    $stmt = DB::prepare("SELECT count(`post_id`) FROM `posts`");
    if (!$stmt->execute())
    {
        $stmt = null;
        print("Error getting post count");
        exit;
    }
    if (!($return = $stmt->fetchAll()))
    {
        $stmt = null;
        return (0);
    }
    $postcount = intval($return[0][0]);
    return (ceil($postcount / 5));
}

?>
