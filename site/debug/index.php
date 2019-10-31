<?php

include_once("../../config/output.php");

output_head("Debug Info", "<style>table { border-collapse: collapse; }
      td, th { border: 1px solid #aaaaaa;
        color: black; padding: 8px; } </style>");
?>

    <table>
      <tr>
        <td>Server url:</td>
        <td><?php print($_SERVER["SERVER_NAME"]); ?></td>
      </tr>
      <tr>
        <td>Server Address:</td>
        <td><?php print($_SERVER["SERVER_ADDR"]); ?></td>
      </tr>
      <tr>
        <td>Server Port:</td>
        <td><?php print($_SERVER["SERVER_PORT"]); ?></td>
      </tr>
      <tr>
        <td>Your Address:</td>
        <td><?php print($_SERVER["REMOTE_ADDR"]); ?></td>
      </tr>
      <tr>
        <td>Your Port:</td>
        <td><?php print($_SERVER["REMOTE_PORT"]); ?></td>
      </tr>
      <tr>
        <td>Server Webmaster:</td>
        <td><?php print($_SERVER["SERVER_ADMIN"]); ?></td>
      </tr>
      <tr>
        <td>Current file:</td>
        <td><?php print($_SERVER["SCRIPT_FILENAME"]); ?></td>
      </tr>
    </table>
    <br />
    <a href="info">PHP Info</a><br /><br />
    <table>
      <tr>
        <th>Header key</th>
        <th>Header value</th>
      </tr>

<?php

foreach (getallheaders() as $key => $value) {
    print("<tr><td>" . $key . "</td><td>" . $value . "</td></tr>");
}

?>

    </table>

<?php output_end(); ?>
