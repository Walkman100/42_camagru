<?php

require_once("../config/output.php");

session_start();

output_head("Resend Email Verification");

output_header();

?>

<br /><br />
<div class='form'>
    <h4>Resend Verification</h4>
    <form method="POST" action="api/account">
            <input type="hidden" name="action" value="resend">
            <input class='forminput' required autofocus type="email" name="email" placeholder="Email Address" />
      <br /><button class='submitbtn' type="submit">Send</button>
    </form>
    <br /><a href="/create">Create Account</a>
    <br /><a href="/reset">Reset Password</a>
</div>

<?php

output_footer();

output_end();

?>
