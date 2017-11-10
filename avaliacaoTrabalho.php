
<?php 
	session_start();
		if(empty($_SESSION['rev'])){

			header("Location:login.php");
		}
	// $_SESSION['id_user'];
	$db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
<!DOCTYPE html>
<html>   
	<head>
		<title>Avaliando</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="estilo.css">
		<script>
			function verifica() {
		      var n1 = document.getElementById("n1").value;
		      if(n1 == null || n1 == "") {
		        alert("O valor é obrigatório!");
		        return false;
		      }
		      var n2 = document.getElementById("n2").value;
		      if(n2 == null || n2 == "") {
		        alert("O valor é obrigatório!");
		        return false;
		      }
		      var n3 = document.getElementById("n3").value;
		      if( n3 == null ||n3  == "") {
		        alert("O valor é obrigatório!");
		        return false;
		      }
		      var n4 = document.getElementById("n4").value;
		      if( n4 == null || n4 == "") {
		        alert("O valor é obrigatório!");
		        return false;
		      }
		  	}
		  	function calculamedia(){
			    var n1 = parseInt(document.getElementById('n1').value);
			    var n2 = parseInt(document.getElementById('n2').value);
			    var n3 = parseInt(document.getElementById('n3').value);
			    var n4 = parseInt(document.getElementById('n4').value);
				var calc = ((n1+n2+n3+n4)/4);
				document.getElementById('media').innerHTML = (calc);
			}
		</script>
	</head>
	<body>
		<a href="login.php?logout=1">
			<img src="index-ifrs.jpg" alt= "logo do ifrs" width="100" height="80">
		</a> 	
		<h3>tela de avalição</h3>
		<p>Olá voce está avaliando um trabalho:</p>

		<fieldset><legend>Resumo</legend>
			<div>

				<?php 
					$id_trabalho=$_GET['id_trabalho'];
					$r=$db->query("SELECT titulo_resumo,resumo,id_autor FROM trabalho where id_trabalho = $id_trabalho");
					foreach ($r as $linha) {
						echo "<fieldset><legend>Título</legend> $linha[0] <br><br></fieldset>";
						echo "<fieldset><legend>Texto do resumo</legend>$linha[1]</fieldset>";
					}
				?>
			</div>
		</fieldset><br>
		<p> Abaixo voce avalia os quesitos:</p>
		<fieldset>
				<legend>Avaliação</legend>
				<div>
					<form action="avaliado.php" id="f1" method="GET" onsubmit="return verifica();">
						<label for="notaOrtografia">Nota Ortografia : </label>
						<input type="number" id="n1" step=0.1 max="10" name="notaOrtografia" placeholder="0">

						<label for="notaClareza">Nota Clareza : </label>
						<input type="number" id="n2" step=0.1 max="10" name="notaClareza" placeholder="0">

						<label for="notaRelevancia">Nota Relevancia : </label>
						<input type="number" id="n3" step=0.1 max="10" name="notaRelevancia" placeholder="0">

						<label for="notaOriginalidade">Nota Originalidade : </label>
						<input type="number" id="n4" step=0.1 max="10" name="notaOriginalidade" placeholder="0" onblur="calculamedia()"><br><br>
						
						<input type="hidden" name="id_autor" value=
						<?php 
							echo $linha[2];
						?>>
						<input type="hidden" name="id_trabalho" value=
						<?php 
							echo $id_trabalho;
						?>>
						<input type="hidden" name="titulo_resumo" value=
						<?php 
							echo $linha[0]; 
						?>>
						<label for="comentario">Comentario :</label><br>
						<textarea type="text" name="comentario" rows="10" cols="30" placeholder="Seu comentario é opcional : "></textarea>
							<fieldset>
								<label>Nota geral : </label>
								<fieldset>
								<p id="media">Aqui vai ser mostrado a media calculada!</p>
								</fieldset>
							</fieldset><br>
					<input type="submit"  value="Avaliar"><br><br></form>	
							
				</div>
		</fieldset><br>
		
		<a href="login.php?logout=1">Encerrar Sessão</a>
	</body>
</html>



