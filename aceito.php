<?php 
	session_start();
	if(empty($_SESSION['org'])){
	     header("Location:login.php");
	}
	$db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if($_GET['aceito']=="true"){
		$atualizastatus = $db->QUERY("UPDATE trabalho SET status=4 where id_trabalho = $_GET[id_trabalho]");

		$aceito = $db-> QUERY("UPDATE avaliacao set aceito=true where id_trabalho=$_GET[id_trabalho]");
		echo "trabalho aceito";
		header("Location:tela_org.php");
	}
	elseif($_GET['recusado']) {
		$recusado = $db-> QUERY("UPDATE avaliacao set recusado=true where id_trabalho=$_GET[id_trabalho]");
		$atualizastatusrecusa = $db->QUERY("UPDATE trabalho SET status=5 where id_trabalho = $_GET[id_trabalho]");
		echo "trabalho recusado";
		header("Location:tela_org.php");
	}
?>