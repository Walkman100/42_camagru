<?php

require_once('../config/globals.php');
require_once('../config/output.php');
require_once('../config/func_user.php');

session_start();

if (!isset($_SESSION['username']))
{
    header('Location: /login');
    exit;
}

if ($_SESSION['username'] === $ADMIN_USER)
{
    header('Location: /admin');
    exit;
}

function printenable(int $opt)
{
    $isnotify = notify_is_on($_SESSION['username']);
    if ($opt === 1 && $isnotify || $opt === 2 && !$isnotify)
        print('checked');
}

output_head('Profile');

output_header();

?>

<br />  <!-- Change Username -->
<div class='form'>
    <h4>Change Username</h4>
    <form method='POST' action='api/account' id='form1' onsubmit="return submitForm('form1');">
            <input class='forminput' required type='text' name='username' placeholder='New Username'
                    pattern='<?php print($USERNAME_REGEX); ?>' title='<?php print($USERNAME_HINT); ?>' />
      <br /><button type='submit' class='submitbtn' name='action' value='changeusername'>Submit</button>
    </form>
</div>

<br />  <!-- Change Password -->
<div class='form'>
    <h4>Change Password</h4>
    <form method='POST' action='api/account' id='form2' onsubmit="return submitForm('form2');">
            <input class='forminput' required type='password' name='oldpassword' placeholder='Old Password' />
      <br /><input class='forminput' required type='password' name='newpassword' placeholder='New Password'
                    pattern='<?php print($PASSWORD_REGEX); ?>' title='<?php print($PASSWORD_HINT); ?>' />
      <br /><button type='submit' class='submitbtn' name='action' value='changepw'>Submit</button>
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
        { ?>
            <div class='small'>Pending Email address:</div>
            <div class='small'><?php print($newemail); ?></div>
            <form method='POST' action='api/account' id='form3' onsubmit="return submitForm('form3');">
                <div class='small'>(Check your inbox)
                    <input type='hidden' name='email' value='<?php print($newemail); ?>' />
                    <button type='submit' name='action' value='resend'>Resend</button>
                </div>
            </form>
            <br />
        <?php }
    ?>
    <form method='POST' action='api/account' id='form4' onsubmit="return submitForm('form4');">
            <input class='forminput' required type='email' name='newemail' placeholder='New Email Address' />
      <br /><button type='submit' class='submitbtn' name='action' value='changeemail'>Submit</button>
    </form>
</div>

<br />  <!-- Change Notify Option -->
<div class='form'>
    <h4>Change Notify Option</h4>
    <form method='POST' action='api/account' id='form5' onsubmit="return submitForm('form5');">
              <label>
                <input type='radio' name='notify' value='true' <?php printenable(1); ?> >
                Enable
              </label>
        <br /><label>
                <input type='radio' name='notify' value='false' <?php printenable(2); ?>>
                Disable
              </label>
        <br /><br /><button type='submit' class='submitbtn' name='action' value='changenotify'>Change</button>
    </form>
</div>

<br />  <!-- Delete Account -->
<div class='form'>
    <h4>Delete Account</h4>
    <form method='POST' action='api/account' id='form6' onsubmit="return submitForm('form6');">
            <input class='forminput' required type='password' name='password' placeholder='Password' />
      <br /><button type='submit' class='submitbtn' name='action' value='delete'>Delete</button>
    </form>
</div>

<?php

output_footer();

output_end();

?>
