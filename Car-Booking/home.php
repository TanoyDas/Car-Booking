<?php
include 'inc/header.php';
include 'lib/user.php';
Session::checkSession();
$user = new User();

?>

<?php
$loginmsg = Session::get("loginmsg");
if (isset($loginmsg)) {
	echo $loginmsg;
}
Session::set("loginmsg", NULL);
?>
<?php
$user = new User();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
	$usrReserv = $user->userReservation($_POST);
}
?>


<div class="panel panel-default">
	<div class="panel-heading">
		<h2>User Reservation<span class="pull-right"><strong>Welcome</strong>
			<?php
				$name = Session::get("name");
				if (isset($name)) {
					echo $name;
				}
			?>

		</span></h2>
	</div>
	
<div class="text-content container"> 
            <div class="inner contact">
                <div class="contact-form">

<?php
$msg = Session::get("msg");
if (isset($usrReserv)) {
	echo $usrReserv;
}
Session::set("msg", NULL);
?>

                    <form id="contact-us" method="POST" action="">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-8 col-md-6 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-6">
                                            <!-- <input type="text" name="first_name" id="first_name" required="required" class="form" placeholder="First Name" /> -->
                                            <label>Select Pickup Point </label>
                                            <input  class="form" name="pickup" list="pickup" required="required" >
                                           
												<datalist id="pickup">
													<option value="Location 01">
													<option value="Location 02">
													<option value="Location 03">
												</datalist> 
                                            <label>Booking Date</label>
                                            <input type="date" name="bookingdate" id="bookingdate" required="required" class="form"/>
											<label>Return Date</label>
                                            <input type="date" name="returndate" id="returndate" required="required" class="form"/>
                                            <label>Select Car number </label>
                                            <input  class="form" name="carnumber" list="carnumber" required="required" >
                                           
												<datalist id="carnumber">
													<option value="Car 01">
													<option value="Car 02">
													<option value="Car 03">
												</datalist>                                        
										</div>

                                        <div class="col-lg-6 col-md-6 col-xs-6">
                                        <label>Select Destination Point</label>
                                       <input type="text" class="form" name="destination" list="destination">
												<datalist id="destination">
													<option value="Location 01">
													<option value="Location 02">
													<option value="Location 03">
												</datalist>
											<label>Booking Time</label>
                                            <input type="time" name="bookingtime" id="bookingtime" required="required" class="form"/>                                            
											<label>Email</label>
                                            <input type="text" name="email" id="email" required="required" class="form"/> 
                                            <label>Number of Passengers </label>
                                            <input type="text" name="passengers" id="passengers" required="required" class="form"/>
                                            </div>

                                        <div class="col-xs-6 ">
                                            <button type="submit"  name="register" class="text-center form-btn form-btn">Reserve</button> 

                                        </div>
                                        <br>
                                    </div>
                                </div>
                                
                           
                            </div>
                        </div>
                     <!--    <div class="clear"></div> -->
                    </form>
                </div>
            </div>
        </div>
                    
</div>

<?php
include 'inc/footer.php';
?>