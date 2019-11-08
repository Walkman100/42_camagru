<?php

require_once('../config/globals.php');
require_once('../config/output.php');

session_start();

output_head('Camagru');

output_header();

?>

<br /><br />
<div class='form'>
    <h2>Welcome</h2>
          <a href='/posts'>Posts</a><br />


    <?php if (isset($_SESSION["username"])) { ?>
        <br /><a href='/logout'>Logout</a><br />
        <br /><a href='/post'>Create Post</a><br />
        <br /><a href='/profile'>Profile</a><br />
    <?php } else { ?>
        <br /><a href='/login'>Login</a><br />
        <br /><a href='/create'>Create Account</a><br />
    <?php } ?>

    <br /><a href='/reset'>Reset Password</a><br />
    <br /><a href='/resend'>Resend Verification Email</a>
</div>

<?php

output_footer();

output_end();

?>
