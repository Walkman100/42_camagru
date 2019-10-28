<?php

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
    $userid = $return[0][0];

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

?>
