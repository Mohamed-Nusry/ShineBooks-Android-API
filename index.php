<?php
require_once('server/dbcon.php');
if($_SERVER["REQUEST_METHOD"]=="GET")
{
	if(isset($_GET['api_key']) && $_GET['api_key']!=null && $_GET['api_key']!=""){

		$api_key=$_GET['api_key'];
		//echo $api_key;

		$csql="SELECT u_secret,u_activation FROM tbl_customer WHERE u_secret=? LIMIT 1";


		$stmt = mysqli_prepare($con,$csql);


		if(mysqli_stmt_bind_param($stmt,"s",$api_key)){
			$result=mysqli_stmt_execute($stmt);
			$activation=0;
			if($result){
				mysqli_stmt_bind_result($stmt,$secret,$activate);
				while (mysqli_stmt_fetch($stmt)) {
					$activation=$activate;
				}

				mysqli_stmt_close($stmt);
				if($activation==1){
					$booksql="SELECT b_id,b_name,b_isbn,b_author,b_price,b_image,b_purchased FROM tbl_book";
					$bookstmt=mysqli_prepare($con,$booksql);
					$bookresult=mysqli_stmt_execute($bookstmt);

					if($bookresult){
						mysqli_stmt_bind_result($bookstmt,$bid,$bname,$bauthor,$bisbn,$bprice,$bimage,$bpurchased);

						$book_array=array();
						while (mysqli_stmt_fetch($bookstmt)) {
							$temp_array["bid"]=$bid;
							$temp_array["bname"]=$bname;
							$temp_array["bisbn"]=$bisbn;
							$temp_array["bauthor"]=$bauthor;
							$temp_array["bprice"]=$bprice;
							$temp_array["bimage"]=$bimage;
							$temp_array["bpurchased"]=$bpurchased;

							array_push($book_array, $temp_array);
						}

						echo json_encode(array("book_details"=>$book_array),JSON_PRETTY_PRINT);
						header('Content-Type: application/json');

					}
				}else{
					echo "access denied";
				}
			}


		}else{
			echo json_encode(array('result' => "Api Key not found" ));
		}
	}else{
		echo json_encode(array('result' => "Api Key not found" ));
	}
	
}

	if($_SERVER["REQUEST_METHOD"]=="POST"){
		$bname=$_POST['bname'];
		$bpurchased=1;
		


		$query ="UPDATE tbl_book SET `b_purchased`=? WHERE `b_name`=?";

		$stmt=mysqli_prepare($con,$query);
		$bind= mysqli_stmt_bind_param($stmt,"is",$bpurchased,$bname);

		$result=mysqli_stmt_execute($stmt);

		if($result){
			echo json_encode(array("res"=>"data inserted"));
		}
		else{
			echo json_encode(array("res"=>"data not inserted"));
		}


	}



	?>