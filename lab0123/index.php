<?php
session_start();

require_once "src/classes/User.php";

$user = new User();

$ret = $user->addUser("e@e.com", "password", "Thomas", "234234");

print_r($ret);

$ret = $user->deleteUser(18);

print_r($ret);

?>
<form id="login" method="POST" action="index.php">
  <input type="hidden" name="login" value="1"><!-- Must have a field other than the button for Mink -->
  <input type="submit" value="logg inn">
</form>
<form id="logout" method="POST" action="index.php">
  <input type="hidden" name="logout" value="1"><!-- Must have a field other than the button for Mink -->
  <input type="submit" value="logg ut">
</form>

<?php
  if ($user->loggedIn()) {
    echo "<h1>Hemmelig</h1>";
  } else {
    echo "<p>Ikke logget inn</p>";
  }
 ?>
