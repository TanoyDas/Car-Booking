<?php
	include_once 'Session.php';
	include 'Database.php';

class User
{
	private $db;
	public function __construct()
	{
		$this->db = new Database();
	}
	public function userRegistration($data)
	{
		$name     = $data['name'];
		$username = $data['username'];
		$email    = $data['email'];

		$chk_email = $this->emailCheck($email);

		$password = $data['password'];
		if (strlen($password)<6) {
			$msg= "<div class='alert alert-danger'>
			<strong>Error !</strong>Password must be more than 6 digit</div>";
			return $msg;
		}
		$password = md5($data['password']);

		if ($name == "" OR $username == "" OR $email  == "" OR $password == "") {
			$msg= "<div class='alert alert-danger'>
			<strong>Error  !</strong> Field must not be Empty </div>";
			return $msg;
		}

		if (strlen($username)<3) {
			$msg= "<div class='alert alert-danger'>
			<strong>Error !</strong>Username is too Short</div>";
			return $msg;
		}elseif (preg_match('/[^a-z0-9_-]+/i', $username)) {
			$msg= "<div class='alert alert-danger'>
			<strong>Error !</strong>Username must only contain alphanumerical, dash, underscore</div>";
			return $msg;
		}

		if (filter_var($email, FILTER_VALIDATE_EMAIL)=== false) {
			$msg= "<div class='alert alert-danger'>
			<strong>Error !</strong>Email address is not valid</div>";
			return $msg;
		}

		if ($chk_email== true) {
			$msg= "<div class='alert alert-danger'>
			<strong>Error !</strong>Email address already exist</div>";
			return $msg;
		}

		$sql = "INSERT INTO db_user (name, username, email, password) VALUES(:name, :username, :email, :password)";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':name', $name);
		$query->bindValue(':username', $username);
		$query->bindValue(':email', $email);
		$query->bindValue(':password', $password);
		$result = $query->execute();
		if ($result) {
			$msg= "<div class='alert alert-success'>
			<strong>Success !</strong>Thank you. You have been regestered</div>";
			return $msg;
		}
		else{
			$msg= "<div class='alert alert-danger'>
			<strong>Error !</strong>Sorry, there have been problem in inserting data</div>";
			return $msg;
		}

	}

	public function emailCheck($email)
	{
		$sql= "SELECT email FROM db_user WHERE email = :email";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':email', $email);
		$query->execute();
		if ($query->rowCount()>0) {
			return true;
		}else{
			return false;
		}
	}

	public function getLoginUser($email, $password)
	{
		$sql= "SELECT * FROM db_user WHERE email = :email AND password = :password LIMIT 1" ;
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':email', $email);
		$query->bindValue(':password', $password);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);
		return $result;
	}


	public function userLogin($data)
	{
		$email    = $data['email'];
		$password = md5($data['password']);

		$chk_email = $this->emailCheck($email);

		if ($email  == "" OR $password == ""){
			$msg= "<div class='alert alert-danger'>
			<strong>Error  !</strong> Field must not be Empty </div>";
			return $msg;
		}

		if (filter_var($email, FILTER_VALIDATE_EMAIL)=== false) {
			$msg= "<div class='alert alert-danger'>
			<strong>Error !</strong>Email address is not valid</div>";
			return $msg;
		}

		if ($chk_email== false) {
			$msg= "<div class='alert alert-danger'>
			<strong>Error !</strong>Email address not exist</div>";
			return $msg;
		}

		$result = $this->getLoginUser($email, $password);
		if ($result) {
			Session::init();
			Session::set("login", true);
			Session::set("id", $result->id);
			Session::set("name", $result->name);
			Session::set("username", $result->username);
			Session::set("loginmsg", "<div class='alert alert-success'>
			<strong>Success !</strong>You are logged in</div>");
			header("Location: home.php");
		}else{
			$msg= "<div class='alert alert-danger'>
			<strong>Error !</strong>Data not found</div>";
			return $msg;
		}

	}

	public function userReservation($data)
	{

		$pickup    				= $data['pickup'];
		$bookingdate      		= $data['bookingdate'];
		$returndate     		= $data['returndate'];
		$carnumber     			= $data['carnumber'];
		$destination     		= $data['destination'];
		$bookingtime     		= $data['bookingtime'];
		$email     				= $data['email'];
		$passengers    			= $data['passengers'];


		$clash = $this -> clashCheck($carnumber, $bookingdate, $bookingtime);

		if ($clash == true) {
			$msg= "<div class='alert alert-danger'>
			<strong>Error !</strong>Reservation already exist</div>";
			return $msg;
		}



		$sql = "INSERT INTO db_reservation (pickup, bookingdate, returndate, carnumber, destination, bookingtime, email, passengers) 
				VALUES(:pickup, :bookingdate, :returndate, :carnumber, :destination, :bookingtime, :email, :passengers)";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':pickup', $pickup);
		$query->bindValue(':bookingdate', $bookingdate);
		$query->bindValue(':returndate', $returndate);
		$query->bindValue(':carnumber', $carnumber);
		$query->bindValue(':destination', $destination);
		$query->bindValue(':bookingtime', $bookingtime);
		$query->bindValue(':email', $email);
		$query->bindValue(':passengers', $passengers);
		$result = $query->execute();
		
		

		if ($result) {
			$msg= "<div class='alert alert-success'>
			<strong>Success !</strong>Thank you. You have been apply for Reservation</div>";
			return $msg;
		}
		
		else{
			$msg= "<div class='alert alert-danger'>
			<strong>Error !</strong>Sorry, there have been problem in inserting data</div>";
			return $msg;
		}
	}

	public function clashCheck($carnumber, $bookingdate)
	{
		$sql= "SELECT * FROM db_user WHERE (carnumber = :carnumber) AND (bookingdate = :bookingdate)" ;
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':carnumber', $carnumber);
		$query->bindValue(':bookingdate', $bookingdate);
		// $query->bindValue(':bookingtime', $bookingtime);
		 // $query->execute();
		 $result=  $query->fetch(PDO::FETCH_OBJ);
		if ($query->rowCount()>0) {
			return true;
		}else{
			return false;
		}
	}



	public function userContact($data)
	{
		$name     		= $data['name'];
		$email     		= $data['email'];
		$subject     	= $data['subject'];
		$message     	= $data['message'];

		$sql = "INSERT INTO db_contact (name, email, subject, message) VALUES(:name, :email, :subject, :message)";
		$query = $this->db->pdo->prepare($sql);
		$query->bindValue(':name', $name);
		$query->bindValue(':email', $email);
		$query->bindValue(':subject', $subject);
		$query->bindValue(':message', $message);
		$result = $query->execute();
		if ($result) {
			$msg= "<div class='alert alert-success'>
			<strong>Success !</strong>Thank you. You have been successfully </div>";
			return $msg;
		}
		else{
			$msg= "<div class='alert alert-danger'>
			<strong>Error !</strong>Sorry, there have been problem in inserting data</div>";
			return $msg;
		}
	}	

}

?>