<?php
error_reporting(1);
ini_set('display_errors', 'on');
class Server {

	private $users = [];
	private $statuses = ['status1', 'status2', 'status3', 'status4', 'status5'];

	public function __construct($user = null)
	{
		$this->setDefault();
		try{
			$this->checkUser($user);
		}catch(Exception $e){
			echo 'Message: ' . $e->getMessage();
		}
	}




	public function getStatus()
	{
		$rand = rand(0, count($this->statuses));
		return $this->statuses[$rand];
	}
	
	public function getWheather()
	{
		return rand(-10, 40);
	}
	
	public function response($data, $status = 200) {
		header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        if ( ! is_array($data) )
        	$data = array($data);
        echo json_encode($data);
    }

    private function _requestStatus($code) {
        $status = array(  
            200 => 'OK',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ); 
        return ($status[$code])?$status[$code]:$status[500]; 
    }


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

if ( $_SERVER['REQUEST_METHOD'] !== 'GET' )
	return false;



$request = $_GET;
$method = 'getStatus';
if ( ! empty($request) && ! empty($_SERVER['QUERY_STRING']) )
	$method = $request['method'];

$data['username'] = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : 'a';
$data['password'] = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : 'b';
$api = new Server($data);
$api->response($api->$method());