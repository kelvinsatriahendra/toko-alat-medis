<?php
$mysql_host = "localhost";
$mysql_database = "toko_medis_prima";
$mysql_user = "root";
$mysql_password = "";

$db = new PDO("mysql:host=$mysql_host;dbname=$mysql_database",$mysql_user,$mysql_password);
$query = file_get_contents("toko_medis_prima.sql");
$status = $db->prepare($query);

if($status->execute()){
	echo "
	<script>
	alert('RETRIEVE DATABASE BERHASIL');
	window.location = '../admin/halaman_utama.php';
	</script>
	";
}else{
	echo "Berhasil";
}



?>