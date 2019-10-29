<?php

require_once("setup.php");
require_once("func_user.php");

date_default_timezone_set("Africa/Johannesburg");

/**
 * @param integer   $postid     ID of post to attach the comment to
 * @param string    $name       Username posting comment
 * @param string    $posttext   Text associated with comment
 * @return bool                 True if comment was added successfully, false if user can't be found
 */
function post_comment(int $postid, string $name, string $posttext)
{
    $currdate = date("Y-m-d H:i:s");

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
    return (true);
}

?>
