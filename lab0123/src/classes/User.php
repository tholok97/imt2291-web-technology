<?php

require_once 'DB.php';

class User {

    // user id of current user. -1 means not logged in
    private $uid = -1;

    // database handler. a pdo object
    private $dbh = null;

    // falg to indicate to user if login failed
    public $failedLogin = false;

    // prepare user object.
    // IF username set -> user is trying to log in:
    //      try to log in. give feedback on if it succeeded or not
    // ELSE IF logout set -> user is trying to log out:
    //      unset the uid session variable
    // ELSE IF uid is set as a session variable:
    //      this uid should be the session uid
    public function __construct($dbh) {
        $this->dbh = $dbh;

        if (isset($_POST['username'])) {

            // login with given credentials
            $status = $this->login($_POST['username'], $_POST['password']);

            // if success -> set uid in user object and sessionj
            if ($status['status'] == 'ok') {
                $this->uid = $status['uid'];
                $_SESSION['uid'] = $this->uid;

            // if fail -> indicate so with the flag
            } else {
                $this->failedLogin = true;    
            }


        } else if (isset($_POST['logout'])) {
            unset($_SESSION['uid']);
        } else if (isset($_SESSION['uid'])) {
            $this->uid = $_SESSION['uid'];
        }
    }

    public function addUser($email, $password, $name, $phone) {

        $ret['status'] = 'fail';
        $ret['uid'] = null;
        $ret['errorMessage'] = null;

        if ($this->dbh == null) {
            $ret['errorMessage'] = 'Couldn\'t establish connection to DB';
            return $ret;
        }

        // autofail if one of params are null
        if ($email == '' || $password == '' || $name == '' || $phone == '') {
            $ret['errorMessage'] = 'Bad params received. Can\'t be empty string';
            return $ret;
        }

        // make hash of password
        $hash = password_hash($password, PASSWORD_DEFAULT);


        // try and insert into db
        try {
            $statement = $this->dbh->prepare('INSERT INTO users (email, password, name, phone) VALUES (:email, :password, :name, :phone)');
            $statement->bindParam(':email', $email);
            $statement->bindParam(':password', $hash);
            $statement->bindParam(':name', $name);
            $statement->bindParam(':phone', $phone);
            $statement->execute();

            $ret['uid'] = $this->dbh->lastInsertId();

        } catch (PDOException $ex) {
            $ret['errorMessage'] = 'Couldn\'t add user to DB ('. $ex->getMessage() .')';
            return $ret;
        } catch (Exception $ex) {
            $ret['errorMessage'] = 'Something went wrong with adding user to db (not PDO)';
            return $ret;
        }

        if ($ret['uid'] == null) {
            $ret['errorMessage'] = 'Couldn\'t get id of newly inserted user';
            return $ret;
        }

        $ret['status'] = 'ok';

        return $ret;
    }

    public function login($email, $password) {
        $ret['status'] = 'fail';
        $ret['uid'] = null;
        $ret['errorMessage'] = null;

        if ($this->dbh == null) {
            $ret['errorMessage'] = 'Couldn\'t establish connection to DB';
            return $ret;
        }

        try {
            $statement = $this->dbh->query('SELECT id, email, password FROM users WHERE email="' . htmlspecialchars($email) . '"');
        } catch (PDOException $ex) {
            $ret['errorMessage'] = 'Couldn\'t add user to DB ('. $ex->getMessage() .')';
            return $ret;
        } catch (Exception $ex) {
            $ret['errorMessage'] = 'Something went wrong with adding user to db (not PDO)';
            return $ret;
        }

        // Will return if not going in while-loop or not going in first if-statement
        $ret['errorMessage'] = 'Wrong email';

        // Get the user
        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            // If not an unexpected error when getting user (did not get "wrong" user)
            if ($row['email'] == $email) {
                // If correct password
                if (password_verify($password, $row['password'])) {
                    $ret['status'] = 'ok';
                    $ret['uid'] = $row['id'];
                    $ret['errorMessage'] = null;
                    return $ret;
                }
                else {
                    $ret['errorMessage'] = 'Wrong password';
                }
            }
        }
        return $ret;
    }

    // For test-use only
    public function getAllUsers() {
        $ret['status'] = 'ok';
        $statement = $this->dbh->query('SELECT * from users');

        $i = 0;
        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $ret[$i]['id'] = $row['id'];
            $ret[$i]['email'] = $row['email'];
            $ret[$i]['password_hash'] = $row['password'];
            $ret[$i]['phone'] = $row['phone'];
            $i++;
        }
        return $ret;
    }

    public function deleteUser($uid) {
        
        // initialize return value (fail by default)
        $ret['status'] = 'fail';
        $ret['errorMessage'] = null;

        // attempt to delete user with given uid
        try {

            // prepare and execute delete statement
            $stmt = $this->dbh->prepare('DELETE FROM users WHERE id=:uid');
            $stmt->bindParam(':uid', $uid);
            $success = $stmt->execute();

            // react on success of statement
            if ($success) {
                $ret['status'] = 'ok';
            } else {
                $ret['errorMessage'] = 'Statement did not execute successfully';
            }

        } catch (PDOException $ex) {
            $ret['errorMessage'] = 'PDO error. Couldn\'t delete user. ('. $ex->errorInfo() .')'; 
        } catch (Exception $ex) {
            $ret['errorMessage'] = 'Couldn\'t delete user. ('. $ex->errorInfo() .')'; 
        }

        return $ret;
    }

    public function loggedIn() {
        return $this->uid != -1;
    }

    public function getUid() {
        return $this->uid; 
    }
}
