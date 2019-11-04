<?php

require_once("../config/output.php");
require_once("../config/func_images.php");

session_start();

if (!$_SESSION["username"])
    header("Location: /login");

output_head("Add Post");
output_header();

?>
<h1>Add Post</h1>
<div class='postviewport'>
    <div class='postmain'>
        <form method="POST" action="api/upload" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                  Select Image:
            <br /><input style="width: 100%; height: 25px" required type="file" name="userfile" />
            <br /><button type="submit">Upload</button>
        </form>
    </div>
    <div class='userimages'>

<?php

$images = get_images($_SESSION['username']);
if ($images)
{
    foreach ($images as $image)
    {
        print("<div class='userimage'>");
        print("  <img class='userimage' src=\"/userdata/" . $image['md5'] . ".png\">");
        print("<div class='postdate'>Uploaded on " . $image['upload_date'] . "</div>");
        print("</div>");
    }
}
print("</div>");

print("<div class='overlays'>");
$overlays = get_overlays();
if ($overlays)
{
    foreach ($overlays as $id)
    {
        // print("<div class='overlay'>");
        print("  <img class='overlay' src=\"/overlays/" . $id . ".png\">");
        // print("</div>");
    }
}
print("</div>");

print("</div>");
output_footer();

output_end();

?>
