<?php

require_once("../config/globals.php");
require_once("../config/output.php");

session_start();

output_head("Create Account");

output_header();

?>

<br /><br />
<div class='form'>
    <h4>Create Account</h4>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="create">
              Username:
        <br /><input class='forminput' required autofocus type="text" name="username" />
        <br />Password:
        <br /><input class='forminput' required type="password" name="password"
                    pattern="<?php print($PASSWORD_REGEX); ?>" title="<?php print($PASSWORD_HINT); ?>" />
        <br />Email Address:
        <br /><input class='forminput' required type="email" name="email" />
        <br /><button class='submitbtn' type="submit">Create</button>
    </form>
    <br /><a href="/reset">Reset Password</a>
    <br /><a href="/resend">Resend Validation Email</a>
</div>

<?php

output_footer();

output_end();

?>
