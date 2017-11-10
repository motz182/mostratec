<?php 
  session_start();
  if(isset($_GET["logout"])){
      session_destroy();
      header("Location:login.php");
    }
  $db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $validaacesso = $db->prepare('SELECT * FROM autor WHERE email=? OR senha=?');//faz select
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="estilo.css">
    <title>Mostratec-login</title>
    <meta charset="UTF-8">

    <script>
      function verifica() {
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
  </head>
  <body>
    <a href="login.php?logout=1">
    <img src="index-ifrs.jpg" alt= "logo do ifrs" width="100" height="80">
    </a> 

    <h3>Formulario de login</h3>
    <p>Ola esta eh a pagina de login do sistema da mostra tecnica</p>
    <div>
      <form action="#" method="POST" onsubmit="return verifica();">
<fieldset><br>
        <label for="email">Email :</label>
        <input type="email" id="email" name="usuario_email" placeholder="Digite um e-mail valido ..."><br><br>

        <label for="senha">Senha :</label>
        <input type="password" id="senha" name="usuario_senha" placeholder="Digite a senha aqui."><br><br>
</fieldset><br>
        <input type="submit" id="logar" value="Logar"><br>
        <?php 
          if((isset($_POST['usuario_email'])) && (isset($_POST['usuario_senha']))) {
            $email=$_POST['usuario_email'];
            $senha=$_POST['usuario_senha'];
            $validaacesso->bindValue(1, $email, PDO::PARAM_STR);
            $validaacesso->bindValue(2, $senha, PDO::PARAM_STR);
            $validaacesso->execute();
            $linhas = $validaacesso->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($linhas as $linha) {// percorre o array

              if($linha['email']==$_POST['usuario_email'] && $linha['senha']==$_POST['usuario_senha']){//verifica se senha bate com email cadastrado

                if($linha['organizador']==1){//verifica se é organizador
                    $_SESSION['id_user']=$linha['id_user'];
                    $_SESSION['org']=$linha['id_user'];
                    header("Location:tela_org.php");//leva para a pagina de destino
                  }
                  elseif($linha['revisor']==1){ $_SESSION['id_user']=$linha['id_user'];
                    $_SESSION['rev']=$linha['id_user'];
                    $_SESSION['autor']=$linha['id_user'];;//cria variavel pois ele tambem é um autor
                    header("Location:tela_rev.php");
                    // verifica se é revisor
                  }
                  else{
                    $_SESSION['id_user']=$linha['id_user'];
                    $_SESSION['autor']=$linha['id_user'];;
                    header("Location:telaAutor.php");
                    //se ele não é nenhum dos acima ele é um autor
                  }
              }
             
                // header("Location:login.php");
                
            } 
          }
        ?>
      </form>
    </div><br>
    <a href="formCadastro.php"> Fazer cadastro </a>
  </body>
</html>
