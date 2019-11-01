<?php

require_once("../config/output.php");
require_once("../config/func_user.php");

session_start();

if (!$_GET["hash"])
    output_head("Send Password Reset Email");
else
    output_head("Reset Password");

output_header();

print("<br /><br />");


if (!$_GET["hash"])
{

    ?>
    <div class='form'>
        <form method="POST" action="api/account">
            <input type="hidden" name="action" value="sendreset">
                Email Address:
            <br /><input class='forminput' required type="email" name="email" />
            <br /><button type="submit">Send</button>
        </form>
        <br /><a href="/create">Create Account</a>
        <br /><a href="/resend">Resend Validation Email</a>
    </div>
    <?php

}
elseif ($username = check_password_reset_key($_GET["hash"]))
{

    ?>
    <div class='form'>
        <form method="POST" action="api/account">
            <input type="hidden" name="action" value="resetpw">
            <input type="hidden" name="hash" value="<?php print($_GET["hash"]); ?>">
                  New Password:
            <br /><input class='forminput' required type="password" name="newpassword" />
            <br /><button type="submit">Send</button>
        </form>
    </div>
    <?php

}
else
    print("Key Doesn't exist!" . PHP_EOL);

output_footer();

output_end();

?>
