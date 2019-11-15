<?php

require_once('../config/output.php');
require_once('../config/globals.php');
require_once('../config/func_user.php');

session_start();

if (!isset($_GET['hash']))
    output_head('Send Password Reset Email');
else
    output_head('Reset Password');

output_header();

print('<br /><br />');


if (!isset($_GET['hash']))
{

    ?>
    <div class='form'>
        <h4>Password Reset</h4>
        <form method='POST' action='api/account' id='form' onsubmit="return submitForm('form');">
                <input class='forminput' required autofocus type='email' name='email' placeholder='Email Address' />
          <br /><button type='submit' class='submitbtn' name='action' value='sendreset'>Send</button>
        </form>
        <br /><a href='<?php print($ROOT_PATH); ?>create'>Create Account</a>
        <br /><a href='<?php print($ROOT_PATH); ?>resend'>Resend Verification Email</a>
    </div>
    <?php

}
elseif (check_password_reset_key($_GET['hash']))
{

    ?>
    <div class='form'>
        <h4>Password Reset</h4>
        <form method='POST' action='api/account' id='form' onsubmit="return submitForm('form');">
                <input type='hidden' name='hash' value='<?php print($_GET['hash']); ?>'>
                <input class='forminput' required autofocus type='password' name='newpassword' placeholder='New Password'
                        pattern='<?php print($PASSWORD_REGEX); ?>' title='<?php print($PASSWORD_HINT); ?>' />
          <br /><button type='submit' class='submitbtn' name='action' value='resetpw'>Send</button>
        </form>
    </div>
    <?php

}
else
    print("Key Doesn't exist!" . PHP_EOL);

output_footer();

output_end();

?>
