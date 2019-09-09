<?php
function connectDb(){
	//$dsn	= mysqli_connect("localhost","tabungem_harga_user","R00tHargaUser","tabungem_harga");
	//$dsn	= mysqli_connect("localhost","root","","db_aruna");
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
