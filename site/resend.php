<?php

require_once('../config/globals.php');
require_once('../config/output.php');

session_start();

output_head('Resend Email Verification');

output_header();

?>

<br /><br />
<div class='form'>
    <h4>Resend Verification</h4>
    <form method='POST' action='api/account.php' id='form' onsubmit="return submitForm('form');">
            <input class='forminput' required autofocus type='email' name='email' placeholder='Email Address' />
      <br /><button type='submit' class='submitbtn' name='action' value='resend'>Send</button>
    </form>
    <br /><a href='<?php print($ROOT_PATH); ?>create.php'>Create Account</a>
    <br /><a href='<?php print($ROOT_PATH); ?>reset.php'>Reset Password</a>
</div>

<?php

output_footer();

output_end();

?>
