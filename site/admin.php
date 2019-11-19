<?php

require_once("../config/output.php");
require_once("../config/globals.php");
require_once("../config/connection.php");
require_once("../config/func_images.php");

session_start();

if (!isset($_SESSION["username"]))
{
    header("Location: " . $ROOT_PATH . "login.php");
    exit;
}

if ($_SESSION["username"] !== $ADMIN_USER)
{
    header("Location: " . $ROOT_PATH . "profile.php");
    exit;
}

output_head("Admin Panel");
output_header();

?>

<h1>Admin Panel</h1>
<div class='postviewport'>
    <div class='userimages' style='height: 100%;'>
    <h2>User Images</h2>

<?php

$stmt = DB::prepare("SELECT
        `upload_date`,
        `md5`,
        `users`.`username`
    FROM
        `savedimages`
    INNER JOIN `users` ON `savedimages`.`user_id` = `users`.`id`
    ORDER BY
        `upload_date` DESC
    ;");

if (!$stmt->execute())
{
    $stmt = null;
    print("Error getting images");
    exit;
}
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($images)
{
    foreach ($images as $image)
    {
        print("<div class='userimage'>");
        print("  <img class='userimage' src=\"" . $ROOT_PATH . "userdata/" . $image['md5'] . ".png\">");
        print("  <div class='postdate'>Uploaded on " . $image['upload_date'] . "</div>");
        print("  <div class='postdate'>By " . $image['username'] . "</div>");
        print("  <form method='POST' action='api/posts.php' id='form" . $image['md5'] . "'");
                      print(" onsubmit=\"return submitForm('form" . $image['md5'] . "');\">");
        print("    <input type='hidden' name='md5' value=\"" . $image['md5'] . "\" />");
        print("    <button type='submit' name='action' value='deleteimage' class='delete'>Delete</button>");
        print("  </form>");
        print("</div>");
    }
}

print("</div>"); // userimages

print("<div class='userimages' style='height: 100%;'>");
print("<h2>Overlays</h2>");

$overlays = get_overlays();
if ($overlays)
{
    foreach ($overlays as $id)
    {
        print("<div class='userimage'>");
        print("  <img class='overlay' src=\"" . $ROOT_PATH . "overlays/" . $id . ".png\">");
        print("  <form method='POST' action='api/posts.php' id='form" . $id . "' onsubmit=\"return submitForm('form" . $id . "');\">");
        print("    <input type='hidden' name='id' value=\"" . $id . "\" />");
        print("    <button type='submit' name='action' value='deleteoverlay' class='delete'>Delete</button>");
        print("  </form>");
        print("</div>");
    }
}

?>

</div>

<div class='userimages'>
    <h2>Add Overlay</h2>
    <form method="POST" action="api/upload.php" enctype="multipart/form-data" id='formupload' onsubmit="return submitUploadForm('formupload');">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php print($MAX_UPLOAD_SIZE); ?>" />
            Select Image (Only PNG):
        <br /><input style="width: 200px; height: 23px" required type="file" accept="image/png" name="userfile" />
        <br /><button type="submit" class='submitbtn'>Upload</button>
        <div id="upload-status">&nbsp;</div>
        <div id="upload-progress">&nbsp;</div>
    </form>
</div>

</div>

<?php

output_footer();

output_end();

?>
