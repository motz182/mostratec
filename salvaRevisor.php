<?php 
	session_start();
	$db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$id = $_GET['id_user'];
	$mudar=$_GET['mudar'];

	// echo $mudar;
	if($mudar=="mudar"){
		$editaRevisor = $db->QUERY("UPDATE autor SET revisor=null where id_user = $id");
	}
	else
	{

		$setRevisor = $db->QUERY("UPDATE autor SET revisor=true where id_user = $id");
	}
	echo "salvo como revisor";
	header("Location:tela_org.php")
?>