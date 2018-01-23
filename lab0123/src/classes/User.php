<?php

require_once 'DB.php';

class User {
    private $uid = -1;

    private $dbh = null;

    public function __construct($dbh = null) {
        if ($dbh == null) {
            $this->dbh = DB::getDBConnection();
        } else {
            $this->dbh = $dbh;
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

        $hash = password_hash($password, PASSWORD_DEFAULT);

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

    public function loggedIn() {
        return $this->uid==1;
    }
}
