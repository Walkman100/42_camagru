<?php

require_once("../config/output.php");

session_start();

output_head("Resend Email Verification");

output_header();

?>

<br /><br />
<div class='form'>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="resend">
              Email Address:
        <br /><input class='forminput' required type="email" name="email" />
        <br /><button type="submit">Send</button>
    </form>
    <br /><a href="/create">Create Account</a>
    <br /><a href="/reset">Reset Password</a>
</div>

<?php

output_footer();

output_end();

?>
