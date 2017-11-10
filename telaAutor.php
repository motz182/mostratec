<?php 
  session_start();
  $DATA= date("Y-m-d");
  if(empty($_SESSION['autor'])){
    header("Location:login.php"); 
  }
  $db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $nome = $db->query("SELECT nome FROM autor WHERE id_user = $_SESSION[id_user]");
  $datalimiteEditar = $db->query("SELECT MAX(id),datalimiteEditar FROM datas_limite group by datas_limite.datalimiteeditar order by MAX(id) desc limit 1");
  $limiteenviar = $db->query("SELECT MAX(id),datalimiteEnviar FROM datas_limite group by datas_limite.datalimiteenviar order by MAX(id) desc limit 1");
  $trabs = $db->query("SELECT titulo_resumo,areacnpq,id_trabalho FROM trabalho where id_autor = $_SESSION[id_user] and status=1");

?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="estilo.css">
    <title>Mostratec-Autor-Home</title>
    <meta charset="UTF-8">
  </head>
  <body>
      <a href="login.php?logout=1">
        <img src="index-ifrs.jpg" alt= "logo do ifrs" width="100" height="80">
      </a>
      <h3>Olá 
        <?php 
          foreach ($nome as $linha) {
           echo $linha[0];
            } 
        ?> 
      !</h3>
      <label for="datalimiteEnviar">Data limite para enviar resumos :</label>
      <input type="date" name="datalimiteEnviar" disabled value=
        <?php //aqui vem  o get do banco 
          foreach($limiteenviar as $dataenviar) {
            echo $dataenviar[1];
          }
        ?>
    >   <!--   aqui fecha i input -->
    <br><br> 
    <?php 
    echo "<div><fieldset><p> Você pode editar resumos enquanto ele não foi revisado ou até a data prevista.</p><br>";
      if($DATA<=$dataenviar[1]){// echo $DATA;
        echo"<a href=\"submeterResumo.php\">Submeter Resumo</a>";
        echo "</fieldset></div><br>";
      }
      else {
          echo "</fieldset><br></div><br>";
      }
 foreach($datalimiteEditar as $dataED) {}
      
$mostrar=$db->query("SELECT COUNT(*) AS QTDE FROM trabalho where id_autor=$_SESSION[id_user] and status=1");
        foreach ($mostrar as $mostre) {// echo $mostre[0];
        }
        if($mostre[0]>0 && $DATA<=$dataED[1]){
      
          echo" <label for=\"datalimiteEditar\">Data limite para editar :</label>";
          echo"<input type=\"date\" name=\"datalimiteEditar\" disabled value=\"$dataED[1]\";><br> "; //aqui fecha o input 
          
          if($DATA<=$dataED[1]){ // echo "pode  mostrar";
            echo"<br><div>";
            echo"<fieldset>";
            echo"<legend>Lista dos seus trabalhos</legend>";
            echo"<table border=\"1\">";
            echo"<tr>";
            echo"<td>Nome </td>";
            echo"<td>areaCNPQ </td>";
            echo"<td>Editar</td>";
            echo"<td>Excluir</td>";
            echo"</tr>";
              
            foreach($trabs as $linhas) {
              echo "<tr><td>$linhas[0]</td>";
              echo "<td>$linhas[1]</td>";
              echo "<td><form action=\"editarResumo.php\" method=\"GET\">";
              echo "<input type=\"hidden\" name=\"id_trabalho\" value=\"$linhas[2]\">";
              $_SESSION['id_trabalho']= $linhas[2];
              echo "<input type=\"hidden\" name=\"editar\" value=\"editar\">";
              echo "<input  type=\"submit\" value=\"Editar\">";
              echo "</td></form>";
              echo "<td><form action=\"#\">";
              echo "<input type=\"hidden\" name=\"excluir\" value=\"excluir\">";
              echo "<input type=\"hidden\" name=\"id_trabalho\" value=\"$linhas[2]\">";
              echo "<input  type=\"submit\" value=\"Excluir\" >";
              echo "</td></form></tr>";
            }
          }
            if(!empty($_GET['excluir'])){
              $excluir=$db->query("DELETE FROM trabalho where id_trabalho=$_GET[id_trabalho]");
              header("Location:telaAutor.php");
            }
              echo "</table>";
              echo "</fieldset>";
              echo "</div><br>";
          }
    ?>
    <br><br>
    <!-- exibir data limite para enviar resumos -->
    <!--quando lista resumos abre outra pagina com a lista-->
    <!-- mudar senha -->
    <!-- sair -->
    <a href="login.php?logout=1">Encerrar Sessão</a>
  </body>
</html>
