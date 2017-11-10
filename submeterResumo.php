<?php 
  session_start();
  if((empty($_SESSION['autor']))){
    header("Location:login.php");
  }
  if(isset($_GET['areaCNPQ']) ){
    if ($_GET['areaCNPQ']=="1") {
      header("Location:submeterResumo.php"); 
    }
           
  }
  $db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $nome = $db->query("SELECT nome FROM autor WHERE id_user = $_SESSION[id_user] ");
  $limiteenviar = $db->query("SELECT MAX(id),datalimiteEnviar FROM datas_limite group by datas_limite.datalimiteenviar order by MAX(id) desc limit 1");
  $area = $db->query("SELECT * FROM AreaCNPQ");//faz o select
?>
<!DOCTYPE html>
<html>   
  <head>
    <title>Mostratec-Submeter-Resumo</title>
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
    </a>     
    <h3>Olá <?php 
      foreach ($nome as $linha) {
        echo $linha[0];
      } ?> 
    !</h3>
    <p>Olá esta é a pagina onde são enviados os resumos para o sistema da mostra tecnica</p>
    
    <label for="datalimiteEnviar">Data limite para enviar resumos :</label>
    <input type="date" name="datalimiteEnviar" disabled value=
      <?php //aqui vem  o get do banco 
        foreach($limiteenviar as $dataenviar) {
          echo $dataenviar[1];
        }
      ?>
    ><br><br>   <!-- aqui fecha o input -->
    <div>
      <form action="#" id="f1" method="GET" onsubmit="return verifica();">
        <label for="titulo_resumo">Titulo : </label>
        <input type="text" id="titulo" name="titulo_resumo" placeholder="Digite o titulo do resumo ..."><br><br>
        
        <label for="nome_autores">Autores : </label>          
        <input type="text" id="autor" name="nome_autores" placeholder="Digite aqui os autores ..."><br><br>
        
        <label for="areaCNPQ">AreaCNPQ : </label>    
        
        <select name="areaCNPQ" ><!-- puxar dados do banco-->
           <option   selected value="1">Selecione uma área</option>
            <?php 
              foreach ($area as $selectArea) {
                  echo" <option value=$selectArea[1]> ";
                  echo $selectArea[0].' - '. $selectArea[1];
                  echo "</option>";
              }
            ?>
          <!-- puxa os dados do banco-->
        </select><br><br>

        <label for="resumo">Resumo : </label><br>
        <textarea type="text" name="resumo" rows="15" cols="50" placeholder="Digite seu resumo aqui"></textarea><br><br>
        
        <input type="hidden" name="id_autor" value=<?php echo $_SESSION['id_user'];?>>
        <input type="hidden" name="status" value="1">

        <input type="submit" id="submeter_resumo" value="enviar"><br><br>
      </form>
      <?php

        if(isset($_GET['titulo_resumo'])){

        $verificacao = $db->prepare('SELECT * FROM trabalho WHERE titulo_resumo=?');
        $titulo_resumo = $_GET['titulo_resumo'];
        $verificacao->bindValue(1, $titulo_resumo, PDO::PARAM_STR);
        $verificacao->execute();
        $linhas = $verificacao->fetchAll(PDO::FETCH_ASSOC);

          if($verificacao->rowCount() > 0){//verifica  se nao encontrou nenhuma linha que tem o titulo
            echo "Este trabalho ja esta cadastrado!";
            echo"<script>alert('Este trabalho ja esta cadastrado');</script>";
            if (isset($_SESSION['autor'])) {
              header("Location:telaAutor.php");
            }
            else{
                  header("Location:tela_rev.php");
                }   
          }
          else{// cadastrar
            
            $titulo_resumo = $_GET['titulo_resumo'];
            $nome_autores = $_GET['nome_autores'];
            $areaCNPQ = $_GET['areaCNPQ'];
            $resumo = $_GET['resumo'];
            $id_autor= $_GET['id_autor'];
            $status = $_GET['status'];

            $insereResumo = $db->prepare("INSERT INTO trabalho (titulo_resumo,nome_autores,areaCNPQ,resumo,id_autor,status) 
              VALUES(:titulo_resumo,:nome_autores,:areaCNPQ,:resumo,:id_autor,:status)");
            
            $insereResumo->execute(array(':titulo_resumo' => $titulo_resumo, ':nome_autores' => $nome_autores, ':areaCNPQ'=>$areaCNPQ,':resumo' => $resumo,':id_autor'=>$id_autor, ':status'=>$status));
            
            echo"<script>alert('dados inseridos');</script>";

            // echo "dados inseridos";

            if(isset($_SESSION['autor'])){
              header("Location:telaAutor.php");
            }else{header("Location:tela_rev.php");}
            }
           
        }
     
    ?>
    </div><br> 
    <a href="login.php?logout=1">Encerrar Sessão</a><br><br>
<?php  
    if(isset($_SESSION['autor'])){
     echo" <a href=\"telaAutor.php\">Voltar</a><br><br>";
    }
    else{
      echo" <a href=\"tela_rev.php\">Voltar</a><br><br>";
    }
?>
    <a href="login.php>Encerrar Sessão</a><br><br>
  </body>
</html>
