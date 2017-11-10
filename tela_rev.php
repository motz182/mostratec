<?php 
  session_start();
  $DATA= date("Y-m-d");
  if(empty($_SESSION['rev'])){
    header("Location:telaAutor.php");  
  }
  
  $db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
 
  

  
 
 

  // $_SESSION['id_user'];
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="estilo.css">
    <title>Mostratec-revisor</title>
    <meta charset="UTF-8">
  </head>
  <body>
    <a href="login.php?logout=1">
      <img src="index-ifrs.jpg" alt= "logo do ifrs" width="100" height="80">
    </a>
    <h3>Olá 
      <?php 
        $nome = $db->query("SELECT nome FROM autor WHERE id_user = $_SESSION[id_user] ");
        foreach ($nome as $nomeUser) {
         echo $nomeUser[0];
        } 
     ?> 
    !</h3>
    <p>Você foi selecionado pelo organizador como revisor, mas voce também pode enviar resumos ...</p>
    <label for="datalimiteEnviar">Data limite para enviar resumos :</label>
    <input type="date" name="datalimiteEnviar" disabled value=
        <?php //aqui vem  o get do banco 
         $limiteenviar = $db->query("SELECT MAX(id),datalimiteEnviar FROM datas_limite group by datas_limite.datalimiteenviar order by MAX(id) desc limit 1");
          foreach($limiteenviar as $dataenviar) {
            echo $dataenviar[1];
          }
        ?>
    >   <!-- aqui fecha o input -->
    <br><br> 
    <label for="datalimiteAvaliar">Data limite para avaliar resumos:</label>
    <input type="date" name="datalimiteAvaliar" disabled value=
      <?php //aqui vem  o get do banco 
       $limitedataAvaliar = $db->query("SELECT MAX(id),datalimiteAvaliar FROM datas_limite group by datas_limite.datalimiteavaliar order by MAX(id) desc limit 1");
        foreach($limitedataAvaliar as $data) {
            echo $data[1];
        } 
      ?>
    > <!-- aqui fecha o input -->
    <br><br>
        <!-- mostrar datalimite para avaliar e depois do tempo não mostrar mais o botao ou link -->
    <div>
      <fieldset>
        <p> Você pode editar resumos  enquanto ele não foi revisado ou até a data prevista.</p>
        <p> E outra .....  Você nunca vai poder avaliar eus próprios resumos....</p>
            <!-- exibir um count de quantos resumos ja foram enviados... -->
            <!-- seleciona trabalhos cujo status eh 2 -->
        <?php 
          if($DATA<=$dataenviar[1]){
            echo"<a href=\"submeterResumo.php\">Submeter Resumo</a>";
          }
        ?>
        <br><br>
      </fieldset>
    </div><br><br>
    <?php 
      $mostrar=$db->query("SELECT COUNT(*) AS QTDE FROM trabalho where id_revisor=$_SESSION[id_user] and status=2");
      foreach ($mostrar as $mostre) {// echo $mostre[0];
      }
      if($mostre[0]>0 && $DATA<=$data[1]){// verificar se trabalho esta dentro da data e se tem trabalho atribuido
       echo"<div>";
       echo"<fieldset><legend>Lista de resumos para você avaliar </legend>";
       echo"<table border=\"1\">";
       echo"<tr>";
       echo"<td>Título</td>";
       echo"<td>Data</td>";
       echo"<td>Avaliar</td>";
       echo"</tr>";
      
       $resumoAtribuido=$db->query("SELECT id_trabalho,titulo_resumo,datatrabalho FROM trabalho WHERE id_revisor=$_SESSION[id_user] and status=2");
       foreach ($resumoAtribuido as $avaliar) {
        echo "<form action=\"avaliacaoTrabalho.php\"method=\"GET\">";
        echo "<tr><td>$avaliar[1]</td>";
        echo "<td>$avaliar[2]</td>";
        echo "<td>
        <input type=\"submit\" name=\"avaliar\" value=\"Avaliar\">
        <input type=\"hidden\" name=\"id_trabalho\" value=\"$avaliar[0]\">
        </td></tr></form>";
        }
        echo"     </table>  ";
        echo"   </fieldset>";
        echo" </div><br>";
      }
      $datalimiteEditar = $db->query("SELECT MAX(id),datalimiteEditar FROM datas_limite group by datas_limite.datalimiteeditar order by MAX(id) desc limit 1");
      foreach($datalimiteEditar as $dataED) {}//echo $dataED[1]
      $mostrarparteeditar=$db->query("SELECT COUNT(*) AS QTDE FROM trabalho where id_autor=$_SESSION[id_user] and status=1");
      foreach ($mostrarparteeditar as $editar) {}// echo $editar[0];
    
    
      if($editar[0]>0 && $DATA<=$dataED[1]){
      
        echo" <fieldset><label for=\"datalimiteEditar\">Data limite para editar :</label>";
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
          $trabs = $db->query("SELECT titulo_resumo,areacnpq,id_trabalho FROM trabalho where id_autor = $_SESSION[id_user] and status=1");  
          foreach($trabs as $linhas) {
            echo "<tr><td>$linhas[0]</td>";
            echo "<td>$linhas[1]</td>";
           
            echo "<td><form action=\"editarResumo.php\" method=\"GET\">";
            echo "<input type=\"hidden\" name=\"id_trabalho\" value=\"$linhas[2]\">";
            $_SESSION['id_trabalho']=$linhas[2];
            echo "<input type=\"hidden\" name=\"editar\" value=\"editar\">";
            echo "<input  type=\"submit\" value=\"Editar\">";
            echo "</td></form>";
           
            echo "<td><form action=\"#\">";
            echo "<input type=\"hidden\" name=\"excluir\" value=\"excluir\">";
            echo "<input type=\"hidden\" name=\"id_trabalho\" value=\"$linhas[2]\">";
            echo "<input  type=\"submit\" value=\"Excluir\" >";
            echo "</td></form></tr>";
          } 
          echo "</table>";
          echo "</fieldset>";
          echo "</div></fieldset><br>";
        }
      }
          if(!empty($_GET['excluir'])){
            $excluir=$db->query("DELETE FROM trabalho where id_trabalho=$_GET[id_trabalho]");
            header("Location:tela_rev.php");
          }
    ?>
    <a href="login.php?logout=1">Encerrar Sessão</a><br><br>
    <!-- aqui deve aparecer a lista de resumos a serem avaliados pelo revisor  -->
    <!-- poderia ter feito uma pagina so e dai mostrar so o que cada um precisa(autor e revisor).... -->
  </body>

</html>
