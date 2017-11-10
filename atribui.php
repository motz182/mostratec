<?php 
	if(empty($_GET['select'])){
		header("Location:atribuirResumo.php");
	}
	
	$db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$id_trabalho = $_GET['id_trabalho'];
	$select = $_GET['select'];

	$setRevisor = $db->QUERY("UPDATE trabalho set id_revisor=$select  where id_trabalho = $id_trabalho");

	$setStatus = $db->QUERY("UPDATE trabalho set status=2  where id_trabalho = $id_trabalho");

	echo "atribuido";
	header("Location:atribuirResumo.php");
?>