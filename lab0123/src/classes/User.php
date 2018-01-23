<?php
class User {
  private $uid = -1;

  public function __construct() {
    if (isset($_POST['login'])) {
      $this->uid = 1;
      $_SESSION['uid'] = 1;
    } else if (isset($_POST['logout'])) {
      unset($_SESSION['uid']);
    } else if (isset($_SESSION['uid'])) {
      $this->uid = 1;
    }
  }

  public function loggedIn() {
    return $this->uid==1;
  }
}
