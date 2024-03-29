<?php

require_once("../config/output.php");
require_once("../config/globals.php");
require_once("../config/func_images.php");

session_start();

if (!isset($_SESSION["username"]))
{
    header("Location: " . $ROOT_PATH . "login.php");
    exit;
}

if ($_SESSION["username"] === $ADMIN_USER)
{
    header("Location: " . $ROOT_PATH . "admin.php");
    exit;
}

output_head("Add Post", "<script type='text/javascript' src='" . $ROOT_PATH . "include/overlays.js'></script>");
output_header();

?>

<h1>Add Post</h1>
<div class='postviewport'>
    <div class='postmain'>
        <div id="uploadformdiv">
            <h2>Upload Image</h2>
            <form method="POST" action="api/upload.php" enctype="multipart/form-data" id='formupload' onsubmit="return submitUploadForm('formupload');">
                <input type="hidden" name="MAX_FILE_SIZE" value="<?php print($MAX_UPLOAD_SIZE); ?>" />
                    Select Image (Only PNG):
                <br /><input style="width: 200px; height: 23px" required type="file" accept="image/png" name="userfile" />
                <br /><button type="submit" class='submitbtn'>Upload</button>
                <div id="upload-status">&nbsp;</div>
                <div id="upload-progress">&nbsp;</div>
            </form>
        </div>
        <div id="webcamdiv">
            <h2>Capture Image</h2>
            <div id="wc-overlays"></div>
            <video autoplay playsinline id="videoElement"></video>
            <br />
            <button type='button' class='submitbtn' id='btnstart'>Start</button>
            <button type='button' class='submitbtn' id='btnstop' disabled>Stop</button>
            <button type='button' class='submitbtn' id='btncapture' disabled>Capture</button>
            <br /><br />
            <img src="" id="captureImage">
            <br />
            <button type='button' class='submitbtn' id='btnupload'>Upload</button>
            <div id="wc-upload-status">&nbsp;</div>
            <div id="wc-upload-progress">&nbsp;</div>
            <canvas style="display:none;" id="captureCanvas"></canvas>

            <script type='text/javascript' src='<?php print($ROOT_PATH); ?>include/webcam.js'></script>
        </div>
    </div>
    <div class='userimages'>
        <?php

        $images = get_images($_SESSION['username']);
        if ($images)
        {
            foreach ($images as $image)
            {
                print("<div class='userimage'>");
                print("  <img class='userimage' src=\"" . $ROOT_PATH . "userdata/" . $image['md5'] . ".png\">");
                print("  <div class='postdate'>Uploaded on " . $image['upload_date'] . "</div>");
                print("  <form method='POST' action='api/posts.php' id='form" . $image['md5'] . "' onsubmit=\"return submitMultibuttonForm('form" . $image['md5'] . "');\">");
                print("    <input type='hidden' name='md5' value=\"" . $image['md5'] . "\" />");
                print("    <button type='submit' name='action' value='add' class='select'>Post</button>");
                print("    <button type='submit' name='action' value='deleteimage' class='delete'>Delete</button>");
                print("  </form>");
                print("</div>");
            }
        }
    print("</div>"); // userimages

    print("<div class='overlays'>");
        $overlays = get_overlays();
        if ($overlays)
        {
            foreach ($overlays as $id)
            {
                print("<label><input type='checkbox' form='formupload' name='overlay[]' value='" . $id . "' onchange='overlays();'>");
                print("<img class='overlay' src=\"" . $ROOT_PATH . "overlays/" . $id . ".png\">");
                print("</label> ");
            }
        }
    print("</div>"); // overlays

print("</div>"); // postviewport
output_footer();

output_end();

?>
