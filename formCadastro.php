<?php   
  // session_destroy();
  $db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $data = $db->query("SELECT MAX(id),datalimiteEnviar FROM datas_limite group by datas_limite.datalimiteenviar order by MAX(id) desc limit 1");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF8">
    <link rel="stylesheet" type="text/css" href="estilo.css">
    <script>
      function verificaSenha (senha){ 
        if (senha.value != document.getElementById('senha').value) {
          senha.setCustomValidity('Senha incorreta');
        } 
        else 
        {
          senha.setCustomValidity('');
        }
      }
      function verifica() {
        var nome = document.getElementById("nome").value;
        if(nome == null || nome == "") {
          alert("O nome é obrigatório!");
          return false;
        }
        var email = document.getElementById("email").value;
        if(email == null || email == "") {
          alert("O email é obrigatório!");
          return false;
        }
        var senha = document.getElementById("senha").value;
        if(senha == null || senha == "") {
          alert("A senha é obrigatória!");
          return false;
        }
        return true;
      }
    </script>

    <title>Formulario de cadastro</title>
  </head>
  <body>
    <a href="login.php?logout=1">
      <img src="index-ifrs.jpg" alt= "logo do ifrs" width="100" height="80">
    </a>
    <h3>Formulario de cadastro</h3>
    <p>Ola esta eh a pagina de cadastro de usuarios do sistema da mostra tecnica</p>
    <label for="datalimiteEnviar">Data limite para enviar resumos :</label>
    <input type="date" name="datalimiteEnviar" disabled value=<?php 
      //colocar aqui dentro o get do banco 
    foreach($data as $linha) {
      echo $linha[1];
    }?>><br><br>
    <!-- mostrar datalimite para avaliar e depois do tempo não mostrar mais o botao ou link -->
    <div>
      <fieldset>
        <form action="cadastro.php" method="post" onsubmit="return verifica();">
         <label for="nome">Nome :</label>
         <input type="text" id="nome" name="nome" placeholder="Digite seu nome."><br><br>

         <label for="email">Email :</label>          
         <input type="email" id="email" name="email" placeholder="exemplo@provedor.com"><br><br>

         <label for="areaCNPQ">AreaCNPQ :</label>  
         <select name="areaCNPQ">
          <!-- puxar dados do banco-->
           <option   selected  value="1">Selecione uma área</option>
           <?php
            $r = $db->query("SELECT * FROM AreaCNPQ");//faz o select
            foreach ($r as $linha) {
             echo" <option value=$linha[1]> ";
                echo $linha[0].' - '. $linha[1];
                echo "</option>";
            }
          ?>
         
          </select><br><br>

          <label for="senha" >Senha :</label>
          <input type="password" id="senha" name="senha" placeholder="Digite uma senha ..."><br><br>

          <label for="repetir_senha">Confirmar Senha :</label>
          <input type="password" id="senha" placeholder="Confirme a senha ..." required oninput="verificaSenha(this)"/><br><br>

          <input type="hidden" id="revisor" name="revisor" value="false" disabled>
          </fieldset><br>

          <input type="submit" id="cada" value="cadastrar">
        </form>
    </div><br>
    <a href="login.php"> Fazer Login</a>
  </body>
</html>