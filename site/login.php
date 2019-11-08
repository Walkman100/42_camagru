<?php

require_once('../config/output.php');

session_start();

if (isset($_SESSION['username']))
{
    header('Location: /profile');
    exit;
}

output_head('Login');

output_header();

?>

<br /><br />
<div class='form'>
    <h4>Login</h4>
    <form method='POST' action='api/account' id='form' onsubmit="return submitForm('form');">
              Username:
        <br /><input class='forminput' required autofocus type='text' name='username' />
        <br />Password:
        <br /><input class='forminput' required type='password' name='password' />
        <br /><button type='submit' class='submitbtn' name='action' value='login'>Login</button>
    </form>
    <br /><a href='/create'>Create Account</a>
    <br /><a href='/reset'>Reset Password</a>
    <br /><a href='/resend'>Resend Verification Email</a>
</div>

<?php

output_footer();

output_end();

?>
