<?php
require_once('server/dbcon.php');

if($_SERVER["REQUEST_METHOD"]=="POST"){
	if(isset($_POST['email']) && $_POST['email']!=null && $_POST['email']!=""){
		if(isset($_POST['password']) && $_POST['password']!=null && $_POST['password']!=""){

			$email=$_POST['email'];
			$password=$_POST['password'];

			$sql="SELECT u_username,u_password,u_email,u_address,u_mobile,last_logged FROM tbl_customer WHERE u_email=? AND u_password=? ";
			$stmt=mysqli_prepare($con,$sql); 
			$stmtbind=mysqli_stmt_bind_param($stmt,"ss",$email,$password);

			if($stmtbind){
				$res=mysqli_stmt_execute($stmt);
			}
			if($res){
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_affected_rows($stmt)>0){
					$result=mysqli_stmt_bind_result($stmt,$uname,$upass,$uemail,$uadd,$umob,$ulastlogged);
					$log_msg=array();

					while (mysqli_stmt_fetch($stmt)) {
						$cust_data["username"]=$uname;
						$cust_data["password"]=$upass;
						$cust_data["email"]=$uemail;
						$cust_data["address"]=$uadd;
						$cust_data["mobile"]=$umob;
						$cust_data["last_logged"]=$ulastlogged;
					}
					echo json_encode(array("login_result" => "Success","user_data" => $cust_data));
					header('Content-Type: application/json');
				}else{
					echo json_encode(array("login_result" => "Try again"));
					header('Content-Type: application/json');
				}
			}
			mysqli_stmt_close($stmt);

		}else{
			echo "password";
		}

	}else{
		echo "email";
	}

	mysqli_close($con);
}else{
	echo "this is for req method";
}


?>