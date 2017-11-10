<?php 
	session_start();
	if(empty($_SESSION['org'])){
	     header("Location:login.php");
	}

	$db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$notageral=(($_GET['notaOrtografia']+$_GET['notaClareza']+$_GET['notaRelevancia']+$_GET['notaOriginalidade'])/4);
	$notaOrtografia=$_GET['notaOrtografia'];
	$notaClareza=$_GET['notaClareza'];
	$notaRelevancia=$_GET['notaRelevancia'];
	$notaOriginalidade=$_GET['notaOriginalidade'];
	$comentario=$_GET['comentario'];
	$id_trabalho=$_GET['id_trabalho'];
	$id_revisor= $_SESSION['id_user'];
	$id_autor=$_GET['id_autor'];
	$titulo_resumo=$_GET['titulo_resumo'];

	if (!empty($_GET['notaOriginalidade']) && !empty($_GET['notaClareza']) && !empty($_GET['notaOrtografia']) && !empty($_GET['notaRelevancia']) && !empty($_GET['id_trabalho'])){

		$r = $db->prepare("SELECT id_revisor FROM avaliacao where id_revisor=?");
		$id_revisor= $_SESSION['id_user'];

		$r -> bindValue(1,$id_revisor,PDO::PARAM_STR);
		$r->execute();
		$linhas=$r->fetchAll(PDO::FETCH_ASSOC);
			
		if($r->rowCount()>0){
			echo "<script>alert('Trabalho ja foi avaliado por voce');</script>";
			
			
		} 

		else
		{
			$setStatus = $db->QUERY("UPDATE trabalho set status=3  where id_trabalho = $id_trabalho"); // update do banco status 3

			$setAvaliacao = $db->prepare("INSERT INTO avaliacao (titulo_resumo,id_revisor,id_autor,id_trabalho,notageral,notaOrtografia,notaClareza,notaRelevancia,notaOriginalidade,comentario) VALUES (:titulo_resumo,:id_revisor,:id_autor,:id_trabalho,:notageral,:notaOrtografia,:notaClareza,:notaRelevancia,:notaOriginalidade,:comentario)");

			$setAvaliacao -> execute(array(':titulo_resumo'=>$titulo_resumo,':id_revisor'=>$id_revisor,':id_autor'=>$id_autor,':id_trabalho'=>$id_trabalho,':notageral'=>$notageral,':notaOrtografia'=> $notaOrtografia,':notaClareza'=>$notaClareza,':notaRelevancia'=>$notaRelevancia,':notaOriginalidade'=>$notaOriginalidade,':comentario'=>$comentario));
			
			echo "<script>alert('dados inseridos');</script>";
			
		}
	}
	else
	{

		echo "vc deixou campos vazios";
		
	}
	header("Location:tela_rev.php");
?>