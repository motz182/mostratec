<?php 
  session_start();
  if(empty($_SESSION['org'])){
    header("Location:login.php");
  }
  $db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="estilo.css">
    <title>Tela de atribuição</title>
    <meta charset="UTF-8">
  </head>
  <body>

    <a href="login.php?logout=1">
      <img src="index-ifrs.jpg" alt= "logo do ifrs" width="100" height="80">
    </a> 
   
    <h3>Tela de atribuição</h3>
    <p>Olá esta é a pagina onde os trabalhos são atribuidos aos revisores.</p>
   
    <div>
      <fieldset>
        <table border="1">
          <tr>
             <td>Titulo-Resumo</td>
             <td>Autor</td>
             <td>Area CNPQ</td>
             <td>revisor</td>
             <td>Salvar</td>
          </tr>

          <?php
          $r = $db->query("SELECT titulo_resumo,nome_autores,areacnpq,id_trabalho,id_autor FROM trabalho where status=1");
          foreach($r as $linhas) {
            echo"<form action=\"atribui.php\" method=GET>";
            echo "<tr><td>$linhas[0]</td>";
            echo "<td>$linhas[1]</td>";
            echo "<td>$linhas[2]</td>";
            echo" <td><select name=select>";
            $rs = $db->query("SELECT nome,id_user FROM autor where areacnpq in(SELECT areacnpq FROM trabalho  where id_trabalho=$linhas[3] ) and organizador is not true and revisor is true and id_user<>$linhas[4]");
              foreach ($rs as $linha) {
                echo " <option value=$linha[1]>  $linha[0] </option> ";
              }
           
           echo "</select><td>";
           echo "<input type=submit value=Salvar>";
           echo" <input type=hidden name=id_trabalho value=\"$linhas[3]\" >";
           echo"</td></form></tr>";
          }
          ?>
        </table>
      </fieldset>
    </div>
  
    <a href="login.php?logout=1">Encerrar Sessão</a><br><br>
    <a href="tela_org.php" >voltar</a>
  </body>
</html>
