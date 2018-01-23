<?php

use PHPUnit\Framework\TestCase;

require_once 'src/classes/DB.php';
require_once 'src/classes/User.php';

class UserTest extends TestCase {

    private $dbh = null;

    protected function setup() {

        // setup dbh, make sure database is clean
        $this->dbh = DB::getDBConnection('mysql:dbname=www_lab0123_users_test;host=127.0.0.1');

        if ($this->dbh != null) {
            try {
                $this->dbh->query('DELETE FROM users');
            } catch (PDOException $ex) {
                $this->fail('Couldn\'t setup db ('. $ex->getMessage() .')');
            }
        } else {
            $this->fail('DB::getDBConnection returned null..');
        }

    }

    protected function teardown() {

        // drop all rows in users
        try {
            if ($this->dbh != null) {
                $this->dbh->query('DELETE FROM users');
            }
        } catch (PDOException $ex) {
            $this->fail('Couldn\'t teardown db ('. $ex->getMessage() .')');
        }
    }

    public function testCreateUser() {
        $this->setup();

        $user = new User($this->dbh);

        $this->teardown();
    }

}
