<?php

use PHPUnit\Framework\TestCase;

require_once 'src/classes/DB.php';
require_once 'src/classes/User.php';

class UserTest extends TestCase {

    private $dbh = null;

    protected function setUp() {

        // setup dbh, make sure database is clean
        $this->dbh = DB::getDBConnection('mysql:dbname=www_lab0123_users_test;host=127.0.0.1');

        // check if we actually got a connection
        if ($this->dbh == null) {
            $this->fail('DB::getDBConnection returned null..');
        }

        // create table in database (fail if couldn't do so)
        if (!$this->dbh->query(
            'CREATE TABLE `users` (
              `id` int(11) NOT NULL,
              `email` varchar(255) NOT NULL,
              `password` varchar(255) NOT NULL,
              `name` varchar(255) NOT NULL,
              `phone` varchar(30) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;'
        )) {

            $this->fail('Coudn\'t create table in setup stage of testing');
        }

        
    }

    protected function tearDown() {

        // drop all rows in users (fail if couldn't do so)
        try {
            if ($this->dbh != null) {
                if (!$this->dbh->query('DROP TABLE users')) {
                    $this->fail('Couldn\'t teardown db. Query didn\'t succeed');
                }
            }
        } catch (PDOException $ex) {
            $this->fail('Couldn\'t teardown db ('. $ex->getMessage() .')');
        }
    }

    public function testCreateUser() {

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

    }
	
	public function testLogin() {

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

    }

    public function testDeleteUser() {

        $user = new User($this->dbh);

        // add testuser. store uid
		$ret = $user->addUser('user1@example.com','Pass1','User1','1234567');
        $uid = $ret['uid'];

        // should succeed
        $ret = $user->deleteUser($uid);
        $this->assertEquals(
            'ok',
            $ret['status'],
            'Deleting newly inserted user with id '.$uid.' should not fail'
        );

    }

}
