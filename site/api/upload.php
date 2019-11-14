<?php

require_once('../../config/output.php');
require_once('../../config/globals.php');
require_once('../../config/func_images.php');

session_start();

/**
 * Adds overlays to a base image, and saves it to the specified path. Images must be image/png
 *
 * @param   string      $baseimage_path     Path of the base image to add overlays to
 * @param   string      $newpath            Path to save the generated image to
 * @param   array|null  $overlays           Array of overlay IDs to add
 * @return  void
 */
function add_overlays(string $baseimage_path, string $newpath, array $overlays)
{
    global $server_root;
    // ================   VARIABLES   ================
    // baseimage                        overlay
    // ---------------- string/number ----------------
    // baseimage_path                   overlay_path
    // base_width                       overlay_width
    // base_height                      overlay_height
    //                  overlay_ratio:      ratio of width to height for the image
    //                  overlay_newwidth:   new width for overlay, adjusted to 25% of base_width
    //                  overlay_newheight:  new height for overlay, adjusted to newwidth
    //                  overlay_posX:       position X for overlay on baseimage
    //                  overlay_posY:       position Y for overlay on baseimage
    // --------------------- blob --------------------
    // baseimage                        overlay
    //                  overlay_resized:    overlay resized to new* values
    //
    // everything is handled as png, and alpha channels are also handled appropriately

    $overlay_posX = 10;
    $overlay_posY = 10;

    // get the width and height
    list($base_width, $base_height) = getimagesize($baseimage_path);
    // get the image object
    $baseimage = imagecreatefrompng($baseimage_path);
    // set alpha channel options
    imagesavealpha($baseimage, true);
    imagealphablending($baseimage, true);

    if (isset($overlays) && is_array($overlays))
    {
        foreach ($overlays as $id)
        {
            $overlay_path = $server_root . '/overlays/' . $id . '.png';

            // get original width and height
            list($overlay_width, $overlay_height) = getimagesize($overlay_path);

            // determine w-h ratio, and new values
            $overlay_ratio = $overlay_width / $overlay_height;
            $overlay_newwidth = $base_width * 0.25; // new image's width is 25% of base_width
            $overlay_newheight = $overlay_newwidth / $overlay_ratio;

            // get the image object
            $overlay = imagecreatefrompng($overlay_path);

            // set alpha channel options
            imagesavealpha($overlay, true);
            imagealphablending($overlay, true);

            // resize the overlay image object: create base image
            $overlay_resized = imagecreatetruecolor($overlay_newwidth, $overlay_newheight);
            imagesavealpha($overlay_resized, true);
            imagealphablending($overlay_resized, false);

            // resize the overlay image object: copy the overlay to the resized base
            imagecopyresampled($overlay_resized, $overlay, 0, 0, 0, 0, $overlay_newwidth, $overlay_newheight, $overlay_width, $overlay_height);

            // copy the resized overlay onto the baseimage
            imagecopy($baseimage, $overlay_resized, $overlay_posX, $overlay_posY, 0, 0, $overlay_newwidth, $overlay_newheight);
            $overlay_posX += $overlay_newwidth + 10;

            // free up objects
            imagedestroy($overlay);
            imagedestroy($overlay_resized);
        }
    }

    imagepng($baseimage, $newpath);
    imagedestroy($baseimage);
}

if (!isset($_SESSION['username']))
    output_error('Not logged in', 401);
elseif (!isset($_FILES) || !isset($_FILES['userfile']))
    output_error('No file selected', 400);
elseif (!isset($_FILES['userfile']['error']) || is_array($_FILES['userfile']['error']))
    output_error('Invalid Parameters', 400);
elseif ($_FILES['userfile']['error'] !== UPLOAD_ERR_OK)
{
    switch ($_FILES['userfile']['error'])
    {
        case UPLOAD_ERR_NO_FILE:
            output_error('Client Error: No file sent', 400);
            break;
        case UPLOAD_ERR_PARTIAL:
            output_error('Client Error: Incomplete File', 400);
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            output_error('Client Error: Exceeded filesize limit', 413);
            break;
        case UPLOAD_ERR_CANT_WRITE:
            output_error('Server Error: No write permissions', 500);
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            output_error('Server Error: No temporary directory configured', 500);
            break;
        default:
            output_error('Unknown Error', 400);
            break;
    }
}
elseif ($_FILES['userfile']['size'] > $MAX_UPLOAD_SIZE)
    output_error('Client Error: Exceeded filesize limit', 413);
else
{
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if ($finfo->file($_FILES['userfile']['tmp_name']) !== 'image/png')
        output_error('Invalid filetype', 415);
    elseif ($_SESSION['username'] === $ADMIN_USER)
    {
        if (add_overlay($_FILES['userfile']['tmp_name']))
            print('Overlay Added' . PHP_EOL);
        else
            output_error('Failed to add overlay', 400);
    }
    else
    {
        if (isset($_POST['overlay']) && is_array($_POST['overlay']))
            add_overlays($_FILES['userfile']['tmp_name'], $_FILES['userfile']['tmp_name'], $_POST['overlay']);

        if (add_image($_SESSION['username'], $_FILES['userfile']['tmp_name']))
            print('Image added successfully' . PHP_EOL);
        else
            output_error("Image or User doesn't exist", 400);
    }
}

?>
