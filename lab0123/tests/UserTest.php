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

        // assert that bad params fail

        $badadd = $user->addUser('', 'test', 'test', 'test');
        $this->assertEquals(
            'fail',
            $badadd['status'],
            'Bad params should fail'
        );

        $badadd = $user->addUser('test', '', 'test', 'test');
        $this->assertEquals(
            'fail',
            $badadd['status'],
            'Bad params should fail'
        );

        $badadd = $user->addUser('test', 'test', '', 'test');
        $this->assertEquals(
            'fail',
            $badadd['status'],
            'Bad params should fail'
        );


        $badadd = $user->addUser('test', 'test', 'test', '');
        $this->assertEquals(
            'fail',
            $badadd['status'],
            'Bad params should fail'
        );


        // assert that basic add works
        $status = $user->addUser('test', 'test', 'test', 'test');
        $stmt = $this->dbh->query('SELECT * FROM users');
        $this->assertEquals(
            1,
            $stmt->rowCount(),
            'Number of rows in users should be 1 after insertion'
        );

        $this->teardown();
    }
	
	public function testLogin() {
        $this->setup();

        $user = new User($this->dbh);
		
		// Add testusers
		$user->addUser('user1@example.com','Pass1','User1','1234567');
		$user->addUser('user2@example.com','Pass2','User2','1234567');
		$user->addUser('user3@example.com','Pass3','User3','1234567');
		
		//echo "Users in database:\n";
		//print_r($user->getAllUsers());
		
		// Will fail on email
		$login1 = $user->login("user4@example.com",'pass4');
		$this->assertEquals(
            'fail',
            $login1['status'],
			'Did accept wrong email'
        );
		
		// Will fail on password
		$login2 = $user->login("user1@example.com",'pass4');
		$this->assertEquals(
            'fail',
            $login2['status'],
			'Did accept wrong password'
        );
		
		// Will not fail
		$login3 = $user->login("user2@example.com",'Pass2');
		//print_r($login3);
		$this->assertEquals(
            'ok',
            $login3['status'],
			'Did not login on correct username/password'
        );

        $this->teardown();
    }

}
