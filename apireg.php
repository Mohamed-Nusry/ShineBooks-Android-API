<?php
require_once('server/dbcon.php');

if($_SERVER["REQUEST_METHOD"]=="POST"){
	if(isset($_POST['email']) && $_POST['email']!=null && $_POST['email']!=""){
		if(isset($_POST['username']) && $_POST['username']!=null && $_POST['username']!=""){
			if(isset($_POST['password']) && $_POST['password']!=null && $_POST['password']!=""){

				$email=$_POST['email'];
				$username=$_POST['username'];
				$password=sha1($_POST['password']);
				$api_key=md5($email);
				$activation=1;

				$sql="INSERT INTO tbl_customer (u_username,u_password,u_email,u_secret,u_activation) VALUES (?,?,?,?,?)";

				$stmt=mysqli_prepare($con,$sql);

				$bind=mysqli_stmt_bind_param($stmt,"ssssi",$username,$password,$email,$api_key,$activation);

				if($bind){
					$res=mysqli_execute($stmt);
				}
				if($res){
					echo json_encode(array("result"=>"Register success"));
				}else{
					echo json_encode(array("result"=>"Try again"));
				}
			mysqli_stmt_close($stmt);
	

			}else{
				echo "password";
			}

		}else{
			echo "username";
		}
		
	}else{
		echo "email";
	}

	mysqli_close($con);
}else{
	echo "this is for req method";
}


?>