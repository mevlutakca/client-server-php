<?php

class Server {

	private $users = [];
	private $statuses = ['status1', 'status2', 'status3', 'status4', 'status5'];

	/**
	 * Constructor for Server Class
	 * @param Array $user User Information for access to the methods
	 */
	public function __construct($user = null)
	{
		// Set default user informations 
		$this->setDefault();
		try{
			// Check user cridentials
			$this->checkUser($user);
		}catch(Exception $e){
			echo 'Message: ' . $e->getMessage();
		}
	}



	/**
	 * random wheater status
	 * @return String Wheather status
	 */
	public function getStatus()
	{
		// generate random integer value beetwen 0 and count of wheater statuses
		$rand = rand(0, count($this->statuses));
		return $this->statuses[$rand];
	}
	
	/**
	 * random Wheather temperature
	 * @return int Wheather temperature
	 */
	public function getWheather()
	{
		// generate and returned random integer
		return rand(-10, 40);
	}
	
	/**
	 * generate output from data with headers for json output
	 * @param  Array  $data   data for output
	 * @param  integer $status status of output
	 * @return void
	 */
	public function response($data, $status = 200) {
		// set header for cross domain origin
		header("Access-Control-Allow-Orgin: *");
		// set all methods can be access this server
        header("Access-Control-Allow-Methods: *");
        // set output content type
        header("Content-Type: application/json");

        // prepare data for json output
        if ( ! is_array($data) )
        	$data = array($data);
        echo json_encode($data);
    }

    /**
     * Check user cridentials
     * @param  Array $user information about login user
     * @return boolean if user exists return true else throw an Exception
     */
	private function checkUser($user = null)
	{

		foreach ( $this->users as $row ){
			if ( is_array($row) && $row['username'] === $user['username'] && $row['password'] === MD5($user['password']) ){
				return true;
			} else if ( $row['username'] === $user['username'] && $row['password'] === MD5($user['password']) ){
				return true;
			}
		}

		throw new Exception('Kullanıcı Hatası');
	}

	/**
	 * setting default user information for login cridentials
	 */
	private function setDefault()
	{
		$this->users = [
			[
				'username' => 'a',
				'password' => MD5('b')
			]
		];
	}
}


// If server request different from GET do not generate any response
if ( $_SERVER['REQUEST_METHOD'] !== 'GET' )
	return false;


// request should come with QUERY_STRING
$request = $_GET;
// default method getStatus if not set method for Server Calls
$method = 'getStatus';
if ( ! empty($request) && ! empty($_SERVER['QUERY_STRING']) )
	$method = $request['method'];

// we are using basic authentication for easy login with cridentials
$data['username'] = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : 'a';
$data['password'] = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : 'b';

// create a new Class from Server with login informations
$api = new Server($data);

// generate response with called method
$api->response($api->$method());