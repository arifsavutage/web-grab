<?php
function connectDb(){
	//$dsn	= mysqli_connect("localhost","pojcityc_aruna_root","4run4@POJ","pojcityc_aruna");
	$dsn	= mysqli_connect("localhost","root","","db_aruna");
	
	/*if($dsn){
		echo "konek";
	}else{
		echo "gak konek";
	}*/
	
	return $dsn;
}

function closeDb($connection){
	mysqli_close($connection);
}
?>