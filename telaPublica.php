<?php 
	$DATA= date("Y-m-d");// echo $DATA;

	$db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$trabalhos = $db->query("SELECT COUNT('status=\"4\"') FROM trabalho ");
	$dataexibicao = $db->query("SELECT MAX(id),datalimiteExibir FROM datas_limite group by datas_limite.datalimiteexibir order by MAX(id) desc limit 1");

	foreach ($dataexibicao as $dataexibir) {
		// echo $dataexibir[0];
	}
	// ECHO COUNT('status=4');
	if($DATA>=$dataexibir[1] && COUNT('status=4')>0){

		echo"<!DOCTYPE html>";
		echo"<html>";
			echo"<head>";
				echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"estilo.css\">";
				echo"<title>Trabalhos aceitos</title>";
				echo"<meta charset=\"utf-8\">";
			echo"</head>";
		echo"<body>";
			echo"<a href=\"login.php?logout=1\">";
				echo"<img src=\"index-ifrs.jpg\" alt= \"logo do ifrs\" width=\"100\" height=\"80\">";
			echo"</a>";
			echo"<h3>Mostra Tecnica - Resumos aceitos</h3>";
			echo"<p>Olá, visitante é nesta pagina que são mostrados os resumos aceitos na mostra tecnica do campus IFRS Feliz.</p>";
			$cont=0;
			$podeExibir=$db->query("SELECT titulo_resumo,areacnpq,nome_autores,resumo FROM trabalho where status = 4");
			foreach ($podeExibir as $linha) {
				$cont++;
			
				echo"<fieldset><legend>Resumo nº $cont </legend><div>";
					echo "<fieldset><legend>Título</legend> $linha[0] </fieldset>";
					echo "<fieldset><legend>Area CNPQ</legend>$linha[1]</fieldset>";
					echo "<fieldset><legend>Nome de Autores</legend>$linha[2]</fieldset>";
					echo "<fieldset><legend>Resumo</legend>$linha[3]</fieldset>";
				echo"</div></fieldset><br><br>";
		}
		echo"<br><fieldset>";
		$total=$db->query("SELECT COUNT(*) as qntTotal FROM trabalho");
		foreach ($total as $totalenv) {
			echo "<p>Nº total de resumos enviados: $totalenv[0] </p>"; 
		}
		$aceitos=$db->query("SELECT COUNT(aceito) as totalAceito FROM avaliacao where  aceito is true ");
		foreach ($aceitos as $totalaceitos) {
			echo "<p>Nº total de resumos aceitos : $totalaceitos[0] </p>";
		}
		echo"</fieldset><br>";


		echo"<fieldset>";
		$areaAceitos=$db->query("SELECT COUNT(areacnpq),areacnpq FROM trabalho where status=4 group by areacnpq");
		// echo sum('areacnpq');
		echo"<table border=\"1\"><tr><legend>Total aceitos por área</legend>";
			echo"<td>Área</td>";
			echo"<td>Total de aceitos</td>";
		foreach ($areaAceitos as $aceito) {
			echo"<tr><td>$aceito[1]</td>";
			echo"<td>$aceito[0]</td></tr>";
			

		}
		echo"</table></fieldset><br>";
		echo"<fieldset>";
		$areaRecusados=$db->query("SELECT COUNT(areacnpq),areacnpq FROM trabalho where status=5 group by areacnpq");
		echo"<table border=\"1\"><tr><legend>Total recusados por área</legend>";
			echo"	<td>Área</td>";
			echo"<td>Total de aceitos</td>";
		foreach ($areaRecusados as $recusado) {
			echo"<tr><td>$recusado[1]</td>";
			echo"<td>$recusado[0]</td></tr>";
			

		}
		echo"</table></fieldset>";


		// $ac=$db->query("SELECT nome_areacnpq) FROM areacnpq where nome_areacnpq in (SELECT nome_areacnpq from trabalho where areacnpq=$area[1]) and aceito are true");
		// 			foreach ($ac_areas as $ace) {
		// 				echo "tantos trabalho foram aceitos na area $ac[0]";
		// 			}

		echo"</body>";
		echo"</html>";
	}
	else
	{
		echo"<!DOCTYPE html>";
		echo"<html>";
			echo"<head>";
				echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"estilo.css\">";
				echo"<title>Trabalhos aceitos</title>";
				echo"<meta charset=\"utf-8\">";
			echo"</head>";
		echo"<body>";
			echo"<a href=\"login.php?logout=1\">";
				echo"<img src=\"index-ifrs.jpg\" alt= \"logo do ifrs\" width=\"100\" height=\"80\">";
			echo"</a>";
			echo"<h3>Mostra Tecnica - Resumos aceitos</h3>";
			echo"<p>Olá, visitante esta página está desativada até a data $dataexibir[1], após serão exibidos os trabalhos aceitos.</p>";

			echo"</body>";
			echo"</html>";

	}

?>
