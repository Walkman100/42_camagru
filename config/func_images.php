<?php

require_once("setup.php");
require_once("func_user.php");

date_default_timezone_set("Africa/Johannesburg");
if ($_SERVER['DOCUMENT_ROOT'])
    $server_root = $_SERVER['DOCUMENT_ROOT'];
else
    $server_root = "/Volumes/wtc-mcarter/camagru";

// ==================== savedimages (/userdata/) functions ====================

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
 * @return array|null               Index-based array of user's images, each consisting of an Associative array with [upload_date] and [md5] elements, ordered by newest first
 */
function get_images(string $name)
{
    $stmt = DB::prepare("SELECT
            `upload_date`,
            `md5`
        FROM
            `savedimages`
        INNER JOIN `users` ON `savedimages`.`user_id` = `users`.`id`
        WHERE
            `users`.`username` = :username
        ORDER BY
            `upload_date` DESC
        ;");

    if (!$stmt->execute(array('username' => $name)))
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

// ==================== overlays (/overlays/) functions ====================

/**
 * @param string    $image_path     Path of image to add as an overlay
 * @return void
 */
function add_overlay(string $image_path)
{
    if (!file_exists(realpath($image_path)))
        return (false);

    $stmt = DB::prepare("INSERT INTO `overlays` (`image_id`) VALUES (null)");
    if (!$stmt->execute())
    {
        $stmt = null;
        print("Error adding overlay");
        exit;
    }

    global $server_root;
    return (copy(realpath($image_path), $server_root . "/overlays/" . DB::lastInsertID() . ".png"));
}

/**
 * @param integer   $id             id of overlay to delete and remove from database
 * @return void
 */
function delete_overlay(int $id)
{
    global $server_root;
    if (!file_exists(realpath($server_root . "/overlays/" . $id . ".png")))
        return;

    unlink(realpath($server_root . "/overlays/" . $id . ".png"));

    $stmt = DB::prepare("DELETE FROM `overlays` WHERE `image_id` = :id");
    if (!$stmt->execute(array('id' => $id)))
    {
        $stmt = null;
        print("Error deleting overlay from database");
        exit;
    }
    $stmt = null;
}

/**
 * @return array|null               Index-based array of overlay IDs ordered ascending
 */
function get_overlays()
{
    $stmt = DB::prepare("SELECT `image_id` FROM `overlays` ORDER BY `image_id` ASC");
    if (!$stmt->execute(array()))
    {
        $stmt = null;
        print("Error getting overlays");
        exit;
    }
    if (!$return = $stmt->fetchAll(PDO::FETCH_COLUMN))
    {
        $stmt = null;
        return (null);
    }
    return ($return);
}

/**
 * Adds all .png files in the /overlays/ folder to the database
 * @return void
 */
function add_all_overlays()
{
    DB::exec("TRUNCATE `overlays`");
    $stmt = DB::prepare("INSERT INTO `overlays` (
            `image_id`
        ) VALUES (
            :imageid
        )");

    global $server_root;
    foreach (array_filter(glob($server_root . "/overlays/" . '*.png'), 'is_file') as $file)
    {
        // above gets the full path to each file. remove the path:
        $file = substr($file, strrpos($file, '/') + 1);
        // and clear the extension:
        $file = substr($file, 0, -4);
        if (!$stmt->execute(array('imageid' => $file)))
        {
            $stmt = null;
            print("Error adding overlays");
            exit;
        }
    }
}

?>
