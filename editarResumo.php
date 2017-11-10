<?php
  session_start();
  if((empty($_SESSION['autor']))){
    header("Location:login.php");
  }

  // echo $_SESSION['id_trabalho'];
  $db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $nome = $db->query("SELECT nome FROM autor WHERE id_user = $_SESSION[id_user] ");
  $limiteenviar = $db->query("SELECT MAX(id),datalimiteEnviar 
    FROM datas_limite group by datas_limite.datalimiteenviar order by MAX(id) desc limit 1");
  $area = $db->query("SELECT * FROM AreaCNPQ");//faz o select
?>
<!DOCTYPE html>
<html>   
  <head>
    <title>Mostratec-Editar-Resumo</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="estilo.css">
    <script>
      function verifica() {
        var titulo = document.getElementById("titulo").value;
        if(titulo == null || titulo == "") {
          alert("O titulo é obrigatório!");
          return false;
        }
        var autor = document.getElementById("autor").value;
        if(autor == null || autor == "") {
          alert("O autor é obrigatório!");
          return false;
        }
        var res = document.getElementById("res").value;
        if (res==null || res =="") {
          alert("O resumos é obrigatório!");
          return false;
        }
        return true;
      }
    </script>
    <!-- mostrar datalimite para avaliar e depois do tempo não mostrar mais o botao ou link -->
  </head>
  <body>
    <a href="login.php?logout=1">
    <img src="index-ifrs.jpg" alt= "logo do ifrs" width="100" height="80">
    </a><br>
    <h3>Olá <?php
      foreach ($nome as $linha) {
        echo $linha[0];
      } ?>
    !</h3><br>
    <p>Olá aqui você pode editar o seu resumo...</p>
    <div>
      <form action="editar.php" method="GET" onsubmit="return verifica();">

        <label for="titulo">Titulo : </label>
        <input type="text" id="titulo" name="titulo" placeholder=
        <?php 
          $editar=$db->query("SELECT * FROM trabalho WHERE id_trabalho =$_SESSION[id_trabalho]");foreach ($editar as $trab){} 
            echo $trab[2];
        
        ?>><br><br>
        <label for="nomes">Autores : </label>
        <input type="text" id="autor" name="nomes" placeholder=
        	<?php 
        		echo $trab[3];
        	?>><br><br>
        <label for="areaCNPQ">AreaCNPQ : </label> 
        <select name="areaCNPQ" ><!-- puxar dados do banco-->
           <option selected disabled>
           	<?php
           		echo $trab[4];
           	 ?>
           	 </option>  <!-- puxa os dados do banco-->
        </select><br><br>
        <label for="res">Resumo : </label><br>
        <textarea type="text" name="res" rows="10" cols="30" placeholder=
          <?php 
            echo $trab[5];
          ?>
        ></textarea><br><br>
        <input type="submit" id="submeter_resumo" value="Editar"><br><br>
      </form>
    </div><br>
    <?php  
      if(isset($_SESSION['autor'])){
        echo" <a href=\"telaAutor.php\">Voltar</a><br><br>";
      }
      else{
        echo" <a href=\"tela_rev.php\">Voltar</a><br>";
      }
    ?>
    <a href="login.php?logout=1">Encerrar Sessão</a>
  </body>
</html>