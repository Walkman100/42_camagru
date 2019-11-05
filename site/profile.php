<?php

require_once("../config/output.php");
require_once("../config/func_user.php");

session_start();

if (!$_SESSION["username"])
    header("Location: /login");

function printenable(int $opt)
{
    $isnotify = notify_is_on($_SESSION["username"]);
    if ($opt === 1 && $isnotify || $opt === 2 && !$isnotify)
        print("checked");
}

output_head("Profile");

output_header();

?>

<br />  <!-- Change Username -->
<div class='form'>
    <h4>Change Username</h4>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="changeusername">
              New Username:
        <br /><input class='forminput' required type="text" name="username" />
        <br /><button class='submitbtn' type="submit">Submit</button>
    </form>
</div>

<br />  <!-- Change Password -->
<div class='form'>
    <h4>Change Password</h4>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="changepw">
              Old Password:
        <br /><input class='forminput' required type="password" name="oldpassword" />
        <br />New Password:
        <br /><input class='forminput' required type="password" name="newpassword" />
        <br /><button class='submitbtn' type="submit">Submit</button>
    </form>
</div>

<br />  <!-- Change Email Address -->
<div class='form'>
    <h4>Change Email Address</h4>
    <div class='small'>Current Email Address:</div>
    <div class='small'><?php print(get_email_address($_SESSION['username'])); ?></div>
    <br />
    <?php
        $newemail = new_email_pending($_SESSION['username']);
        if ($newemail)
        {
            print("<div class='small'>Pending Email address:</div>");
            print("<div class='small'>" . $newemail . "</div>");
            print("<div class='small'>(Check your inbox)</div>");
            print("<br />");
        }
    ?>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="changeemail">
              New Email Address:
        <br /><input class='forminput' required type="email" name="newemail" />
        <br /><button class='submitbtn' type="submit">Submit</button>
    </form>
</div>

<br />  <!-- Change Notify Option -->
<div class='form'>
    <h4>Change Notify Option</h4>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="changenotify">
              <label>
                <input type="radio" name="notify" value="true" <?php printenable(1); ?> >
                Enable
              </label>
        <br /><label>
                <input type="radio" name="notify" value="false" <?php printenable(2); ?>>
                Disable
              </label>
        <br /><br /><button class='submitbtn' type="submit">Change</button>
    </form>
</div>

<br />  <!-- Delete Account -->
<div class='form'>
    <h4>Delete Account</h4>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="delete">
              Password:
        <br /><input class='forminput' required type="password" name="password" />
        <br /><button class='submitbtn' type="submit">Delete</button>
    </form>
</div>

<?php

output_footer();

output_end();

?>
