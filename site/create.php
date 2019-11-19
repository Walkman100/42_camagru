<?php

require_once('../config/globals.php');
require_once('../config/output.php');

session_start();

if (isset($_SESSION['username']))
{
    header("Location: " . $ROOT_PATH . "profile.php");
    exit;
}

output_head('Create Account');

output_header();

?>

<br /><br />
<div class='form'>
    <h4>Create Account</h4>
    <form method='POST' action='api/account.php' id='form' onsubmit="return submitForm('form');">
              Username:
        <br /><input class='forminput' required autofocus type='text' name='username'
                    pattern='<?php print($USERNAME_REGEX); ?>' title='<?php print($USERNAME_HINT); ?>' />
        <br />Password:
        <br /><input class='forminput' required type='password' name='password'
                    pattern='<?php print($PASSWORD_REGEX); ?>' title='<?php print($PASSWORD_HINT); ?>' />
        <br />Email Address:
        <br /><input class='forminput' required type='email' name='email' />
        <br /><button type='submit' class='submitbtn' name='action' value='create'>Create</button>
    </form>
    <br /><a href="<?php print($ROOT_PATH); ?>reset.php">Reset Password</a>
    <br /><a href="<?php print($ROOT_PATH); ?>resend.php">Resend Verification Email</a>
</div>

<?php

output_footer();

output_end();

?>
