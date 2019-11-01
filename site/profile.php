<?php

require_once("../config/output.php");

session_start();

if (!$_SESSION["username"])
    header("Location: /login");

output_head("Profile");

output_header();

?>

<br /><br />
<div class='form'>
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
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="changeemail">
              New Email Address:
        <br /><input class='forminput' required type="email" name="newemail" />
        <br /><button type="submit">Submit</button>
    </form>
</div>

<br /><br />
<div class='form'>
    <form method="POST" action="api/account">
        <input type="hidden" name="action" value="changenotify">
        <br /><button type="submit">Change</button>
    </form>
</div>

<br /><br />
<div class='form'>
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
