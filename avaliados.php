<?php 
	session_start();
	if(empty($_SESSION['org'])){
	     header("Location:login.php");
	}
	if(empty($_GET['notaCorte'])){
		header("Location:tela_org.php");
	}
	
	$db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// $notaCorte=$_GET['notaCorte'];
	
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="estilo.css">
		<title>lista de avaliados</title>
		<meta charset="utf-8">
	</head>
	<body>
		<a href="login.php?logout=1">
			<img src="index-ifrs.jpg" alt= "logo do ifrs" width="100" height="80">
		</a> 
		<h3>tela de aceitação</h3>
		<p>Olá esta é a pagina onde você aceita trabalhos revisados...</p>
		
		<div>
			<fieldset>
				<legend>Lista de avaliados</legend>
				<table border="1">
					<tr>
						<td>Titulo </td>
						<td>Nome Revisor </td>
						<td>Autores</td>
						<td>Nota geral</td>
						<td>Comentario</td>
						<td>Recusar</td>
						<td>Aceitar</td>
					</tr>
					<?php 
						$ava = $db->query("SELECT  * from avaliacao where notageral>=$_GET[notaCorte] and aceito is not true and recusado is not true");
						foreach ($ava as $linha) {
						echo "<tr><td>$linha[4]</td>";//titulo do resumo
						echo "<td>";
							$nome = $db -> query("SELECT nome from autor where id_user in (SELECT id_revisor FROM trabalho where id_revisor=$linha[3])");
							foreach ($nome as $num) {
								echo $num[0];//nome do revisor
							}
						echo "</td>";
						echo "<td>";
						$nomeAutor=$db->query("SELECT nome_autores from trabalho where id_trabalho in (SELECT id_trabalho FROM avaliacao where id_autor=$linha[1])");
							foreach ($nomeAutor as $autor) {
								echo $autor[0];
							}
						echo"</td>";
						echo "<td>$linha[5]</td>";
						
						echo "<td>$linha[10]</td>";

						echo "<form action=\"aceito.php\" method=\"GET\">";
						echo "<td> <input  type=\"submit\" value=\"Recusar\">";
						echo "<input type=\"hidden\" name=\"recusado\" value=\"true\">";
						echo "<input type=\"hidden\" name=\"aceito\"value=\"1\">";
						echo "<input type=\"hidden\" name=\"id_trabalho\" value=\"$linha[2]\">";
							
						echo "</td></form>";

						echo "<form action=\"aceito.php\" method=\"GET\">";
						echo "<td> <input  type=\"submit\" value=\"Aceitar\"></td>";
						echo "<input type=\"hidden\" name=\"id_trabalho\" value=\"$linha[2]\">";
						echo "<input type=\"hidden\" name=\"aceito\"value=\"true\">";
						echo "<input type=\"hidden\" name=\"recusado\" value=\"1\">";
						
						echo "</tr></form>";
						}	
					?>
				</table>
			</fieldset>		
		</div>
		<a href="tela_org.php">Voltar</a>
	</body>
</html>
