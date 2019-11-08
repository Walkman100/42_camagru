<?php

require_once("../../config/output.php");
require_once("../../config/globals.php");
require_once("../../config/func_images.php");

session_start();

if (!isset($_SESSION["username"]))
    output_error("Not logged in", 401);
elseif (!isset($_FILES) || !isset($_FILES['userfile']))
    output_error("No file selected", 400);
elseif (!isset($_FILES['userfile']['error']) || is_array($_FILES['userfile']['error']))
    output_error("Invalid Parameters", 400);
elseif ($_FILES['userfile']['error'] !== UPLOAD_ERR_OK)
{
    switch ($_FILES['userfile']['error'])
    {
        case UPLOAD_ERR_NO_FILE:
            output_error("Client Error: No file sent", 400);
            break;
        case UPLOAD_ERR_PARTIAL:
            output_error("Client Error: Incomplete File", 400);
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            output_error("Client Error: Exceeded filesize limit", 413);
            break;
        case UPLOAD_ERR_CANT_WRITE:
            output_error("Server Error: No write permissions", 500);
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            output_error("Server Error: No temporary directory configured", 500);
            break;
        default:
            output_error("Unknown Error", 400);
            break;
    }
}
elseif ($_FILES['userfile']['size'] > $MAX_UPLOAD_SIZE)
    output_error("Client Error: Exceeded filesize limit", 413);
else
{
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if ($finfo->file($_FILES['userfile']['tmp_name']) !== 'image/png')
        output_error("Invalid filetype", 415);
    elseif ($_SESSION["username"] === $ADMIN_USER)
    {
        if (add_overlay($_FILES['userfile']['tmp_name']))
            print("Overlay Added" . PHP_EOL);
        else
            output_error("Failed to add overlay", 400);
    }
    elseif (add_image($_SESSION['username'], $_FILES['userfile']['tmp_name']))
        print("Image added successfully" . PHP_EOL);
    else
        output_error("Image or User doesn't exist", 400);
}

?>
