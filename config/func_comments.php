<?php

require_once("connection.php");
require_once("func_user.php");
require_once("globals.php");

/**
 * @param integer   $postid     ID of post to attach the comment to
 * @param string    $name       Username posting comment
 * @param string    $posttext   Text associated with comment
 * @return bool                 True if comment was added successfully, false if post or user can't be found
 */
function post_comment(int $postid, string $name, string $posttext)
{
    $currdate = date("Y-m-d H:i:s");

    $stmt = DB::prepare("SELECT
            `users`.`username`,
            `users`.`email`,
            `users`.`notify`
        FROM
            `posts`
        INNER JOIN `users` ON `posts`.`user_id` = `users`.`id`
        WHERE
            `post_id` = :postid
        ;");
    if ($stmt->execute(array('postid' => $postid)))
    {
        if (!$return = $stmt->fetchAll())
        {
            $stmt = null;
            return (false);
        }
    }
    else
    {
        $stmt = null;
        print("Error checking if post exists");
        exit;
    }

    if (!$userid = get_user_id($name))
        return (false);

    $stmt = DB::prepare("INSERT INTO `comments` (
            `post_id`, `user_id`, `post_date`, `text`
        ) VALUES (
            :postid, :userid, :postdate, :text
        )");
    if (!$stmt->execute(array(
            'postid' => $postid,
            'userid' => $userid,
            'postdate' => $currdate,
            'text' => $posttext
        )))
    {
        $stmt = null;
        print("Error creating comment");
        exit;
    }

    if ($return[0]['notify'] === '1')
        send_comment_notification($return[0]['username'], $return[0]['email'], $posttext);
    return (true);
}

/**
 * @param integer   $postid     ID of post to delete comments for
 * @return void
 */
function delete_comments(int $postid)
{
    $stmt = DB::prepare("DELETE FROM `comments` WHERE `post_id` = :postid");
    if (!$stmt->execute(array('postid' => $postid)))
    {
        $stmt = null;
        print("Error deleting comments from database");
        exit;
    }
    $stmt = null;
}

/**
 * @param integer   $postid     ID of post to get comments for
 * @return array|null           Index-based array of comments, each consisting of an Associative array with [username], [post_date] and [text] elements, ordered by oldest first
 */
function get_comments(int $postid)
{
    $stmt = DB::prepare("SELECT
            `users`.`username`,
            `post_date`,
            `text`
        FROM
            `comments`
        INNER JOIN `users` ON `comments`.`user_id` = `users`.`id`
        WHERE
            `post_id` = :postid
        ORDER BY
            `post_date` ASC
        ;");

    if (!$stmt->execute(array('postid' => $postid)))
    {
        $stmt = null;
        print("Error getting comments");
        exit;
    }
    if (!$return = $stmt->fetchAll(PDO::FETCH_ASSOC))
    {
        $stmt = null;
        return (null);
    }
    return ($return);
}


?>
