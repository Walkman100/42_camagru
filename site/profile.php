<?php

require_once("../config/output.php");
require_once("../config/func_user.php");

session_start();

if (!$_SESSION["username"])
    header("Location: /login");

function isenable(int $opt)
{
    $isnotify = notify_is_on($_SESSION["username"]);
    if ($opt === 1 && $isnotify || $opt === 2 && !$isnotify)
        print("checked");
}

output_head("Profile");

output_header();

?>

<br /><br />
<div class='form'>
    <h4>Change Password</h4>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="changepw">
              Old Password:
        <br /><input class='forminput' required type="password" name="oldpassword" />
        <br />New Password:
        <br /><input class='forminput' required type="password" name="newpassword" />
        <br /><button type="submit">Submit</button>
    </form>
</div>

<br /><br />
<div class='form'>
    <h4>Change Email Address</h4>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="changeemail">
              New Email Address:
        <br /><input class='forminput' required type="email" name="newemail" />
        <br /><button type="submit">Submit</button>
    </form>
</div>

<br /><br />
<div class='form'>
    <h4>Change Notify Option</h4>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="changenotify">
              <label>
                <input type="radio" name="notify" value="true" <?php isenable(1); ?> >
                Enable
              </label>
        <br /><label>
                <input type="radio" name="notify" value="false" <?php isenable(2); ?>>
                Disable
              </label>
        <br /><br /><button type="submit">Change</button>
    </form>
</div>

<br /><br />
<div class='form'>
    <h4>Delete Account</h4>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="delete">
              Password:
        <br /><input class='forminput' required type="password" name="password" />
        <br /><button type="submit">Delete</button>
    </form>
</div>

<?php

output_footer();

output_end();

?>