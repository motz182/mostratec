<?php
	session_start();
  $db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         
            $titulo = $_GET['titulo'];
            $nomes = $_GET['nomes'];
          	$id_trabalho=$_SESSION['id_trabalho'];
            $resumo = $_GET['res'];
            

            $update = $db ->QUERY("UPDATE trabalho SET titulo_resumo='$titulo',nome_autores='$nomes',resumo='$resumo' WHERE id_trabalho=$_SESSION[id_trabalho]");
            

       
           echo"<script>alert('dados alterados');</script>";
           
           if(isset($_SESSION['autor'])){
        header("Location:telaAutor.php");
      }
      else{
         header("Location:tela_rev.php");
      }
        
     
    ?> 