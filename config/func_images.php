<?php

require_once("setup.php");
require_once("func_user.php");

date_default_timezone_set("Africa/Johannesburg");
if ($_SERVER['DOCUMENT_ROOT'])
    $server_root = $_SERVER['DOCUMENT_ROOT'];
else
    $server_root = "/Volumes/wtc-mcarter/camagru";

/**
 * @param string    $name           Username to associate with image
 * @param string    $image_path     Path of image to add
 * @return string|false             md5 of image if image was added successfully, false if image doesn't exist or user doesn't exist. If the target already exists, the md5 of the image is returned and no changes are made.
 */
function add_image(string $name, string $image_path)
{
    $currdate = date("Y-m-d H:i:s");

    if (!file_exists($image_path))
        return (false);

    if (!$userid = get_user_id($name))
        return (false);

    $md5 = md5_file($image_path);

    global $server_root;
    if (file_exists($server_root . "/userdata/" . $md5 . ".png"))
        return ($md5);

    $stmt = DB::prepare("INSERT INTO `savedimages` (
            `user_id`, `upload_date`, `md5`
        ) VALUES (
            :userid, :uploaddate, :md5
        )");
    if (!$stmt->execute(array(
            'userid' => $userid,
            'uploaddate' => $currdate,
            'md5' => $md5
        )))
    {
        $stmt = null;
        print("Error adding image");
        exit;
    }

    copy(realpath($image_path), $server_root . "/userdata/" . $md5 . ".png");

    return ($md5);
}

/**
 * @param string    $md5            md5 of image to delete and remove from database
 * @return void
 */
function delete_image(string $md5)
{
    global $server_root;
    if (!file_exists(realpath($server_root . "/userdata/" . $md5 . ".png")))
        return;

    unlink(realpath($server_root . "/userdata/" . $md5 . ".png"));

    $stmt = DB::prepare("DELETE FROM `savedimages` WHERE `md5` = :md5");
    if (!$stmt->execute(array('md5' => $md5)))
    {
        $stmt = null;
        print("Error deleting image from database");
        exit;
    }
    $stmt = null;
}

/**
 * @param string    $name           Username to get images for
 * @return array|null               Index-based array of comments, each consisting of an Associative array with [upload_date] and [md5] elements, ordered by newest first
 */
function get_images(string $name)
{
    if (!$userid = get_user_id($name))
        return (null);
    $stmt = DB::prepare("SELECT
            `upload_date`,
            `md5`
        FROM
            `savedimages`
        WHERE
            `user_id` = :userid
        ORDER BY
            `upload_date` DESC
        ;");

    if (!$stmt->execute(array('userid' => $userid)))
    {
        $stmt = null;
        print("Error getting images");
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
