<?php

session_start();

require_once "src/classes/User.php";

$user = new User(DB::getDBConnection());

$user->addUser('testuser', 'testpassword', 'name', '123');

if ($user->failedLogin) {
    echo 'Wrong username / password';
}

?>
<form id="login" method="POST" action="index.php">
    <input id='username' type"text" name="username"/>
    <input id='password' type"password" name="password"/>
    <input type="submit" value="logg inn">
</form>
<form id="logout" method="POST" action="index.php">
  <input type="hidden" name="logout" value="1"><!-- Must have a field other than the button for Mink -->
  <input type="submit" value="logg ut">
</form>

<?php
if ($user->loggedIn()) {
    echo '<h1>Hello ' . $user->getUid() . '</h1>'; // h1 cause test uses it
} else {
    echo "<p>Ikke logget inn</p>";
}
?>
