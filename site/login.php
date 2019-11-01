<?php

require_once("../config/output.php");

session_start();

output_head("Login");

output_header();

?>

<br /><br />
<div class='form'>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="login">
              Username:
        <br /><input class='forminput' required type="text" name="username" value="" />
        <br />Password:
        <br /><input class='forminput' required type="password" name="password" value="" />
        <br /><button type="submit">Login</button>
    </form>
    <br /><a href="/create">Create Account</a>
    <br /><a href="/reset">Reset Password</a>
    <br /><a href="/resend">Resend Validation Email</a>
</div>

<?php

output_footer();

output_end();

?>
