<?php 
session_start();
    $DATA= date("Y-m-d");// echo($DATA);
    if(empty($_SESSION['org'])){
     header("Location:login.php");
    }
    $db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $nome = $db->query("SELECT nome FROM autor WHERE id_user = $_SESSION[id_user] ");
    $indicarRev = $db->query("SELECT id_user,nome,areacnpq,revisor FROM autor where organizador is not true order by nome");
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="estilo.css">
        <title>Mostratec-Organizador</title>
        <meta charset="UTF-8">
        <script>
            function verifica() {
                var areacnpq = document.getElementById("areacnpq").value;
                if(areacnpq == null || areacnpq == "") {
                alert("Campo Vazio!");
                return false;
                }
           	  return true;
            }
            function verificanota() {
            	var notaCorte = document.getElementById("notaCorte").value;
            	if(notaCorte == null || notaCorte == "") {
            		alert("A insersão é obrigatória!");
             		return false;
            	}
           	  return true;
            }
            function verificadata() {
            	var data1 = document.getElementById("data1").value;
            	if(data1 == null || data1 == "") {
            		alert("A insersão é obrigatória!");
             		return false;
            	}
            	var data2 = document.getElementById("data2").value;
            	if(data2 == null || data2 == "") {
            		alert("A insersão é obrigatória!");
             		return false;
            	}
            	var data3 = document.getElementById("data3").value;
            	if(data3 == null || data3 == "") {
            		alert("A insersão é obrigatória!");
             		return false;
            	}

            	var data4 = document.getElementById("data4").value;
            	if(data4 == null || data4 == "") {
            		alert("A insersão é obrigatória!");
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
        
        <h3>Olá 
            <?php 
                foreach ($nome as $linha) {
                    echo $linha[0];
                } 
            ?> 
        !</h3>
        
        <p> Você que administra o evento.</p>
        <p>Abaixo você faz as configurações do evento. </p>

        <fieldset><legend>Datas limite do evento</legend>
            <form action="#" id=f2 method="POST" onsubmit="verificadata()" >
                <div>
                    <label for="datalimiteEnviar">Data limite para enviar :</label>
                    <input type="date" id="data1" name="datalimiteEnviar" value=
                        <?php 
                            echo $DATA; 
                        ?>
                    ><br><br><!-- aqui fech o input  -->
                    
                    <label for="datalimiteEditar">Data limite para editar :</label>
                    <input type="date" id="data2" name="datalimiteEditar" value=
                        <?php 
                            echo $DATA; 
                        ?>
                    ><br><br><!-- aqui fech o input  -->
                   
                    <label for="datalimiteAvaliar">Data limite para avaliar :</label>
                    <input type="date" id="data3" name="datalimiteAvaliar" value=
                        <?php
                            echo $DATA;
                        ?>
                        ><br><br><!-- aqui fech o input  -->
                    <label for="datalimiteExibir">Data para exibição publica :</label>
                    <input type="date" id="data4" name="datalimiteExibir" value=
                        <?php 
                            echo $DATA; 
                        ?>
                    ><br><br><!-- aqui fech o input  -->
                </div><br>
                <input type="submit" name="salvar" value="salvar">
                <?php 
                    if (isset($_POST['salvar'])) {
                
                        $datalimiteEnviar = $_POST['datalimiteEnviar'];
                        $datalimiteEditar = $_POST['datalimiteEditar'];
                        $datalimiteAvaliar = $_POST['datalimiteAvaliar'];
                        $datalimiteExibir = $_POST['datalimiteExibir'];

                        $r = $db->prepare("INSERT INTO datas_limite (datalimiteEnviar,datalimiteEditar,datalimiteAvaliar,datalimiteExibir) VALUES (:datalimiteEnviar,:datalimiteEditar,:datalimiteAvaliar,:datalimiteExibir)");
                        $r->execute(array(':datalimiteEnviar'=>$datalimiteEnviar,':datalimiteEditar'=>$datalimiteEditar,':datalimiteAvaliar'=>$datalimiteAvaliar,':datalimiteExibir'=>$datalimiteExibir));
                        echo"<script>alert('datas salvas');</script>";
                    }
                ?>
            </form>
        </fieldset><br>
        
        <fieldset>  <legend>Indicar para Revisor</legend>
            <div><table border="1">
                <tr>
                    <td>Nome </td>
                    <td>areaCNPQ </td>
                    <td>É Revisor </td>
                    <td>Salvar </td>
                </tr>

                <?php
                foreach($indicarRev as $linha) {
                    echo "<form action=salvaRevisor.php method=GET>";
                    echo "<tr><td>$linha[1]</td>";
                    echo "<td>$linha[2]</td>";
                    echo "<td><input type=checkbox id=$linha[0] name=$linha[0] value=$linha[3] checked disabled></td>";
                    if ($linha[3]==1) {
                    $linha[3]="mudar";
                echo "<input type=hidden name=mudar value=$linha[3]>";
                }
                    echo "<td> <input id=$linha[0] type=submit name=var value=\"Salvar\"></td></tr>";
                    echo "<input type=hidden name=id_user value=$linha[0]>";
                    echo "</form>";
                }
                ?>
            </div></table>
        </fieldset><br>
        <fieldset><legend>Area CNPQ</legend>
            <form action="#" method="GET" onsubmit = "return verifica()">
                <label for="areacnpq" >adicione uma area CNPQ : </label>
                <input type="text" name="areacnpq" id="areacnpq" placeholder="Add area..">
                    <?php 
                        if(!empty($_GET['areacnpq'])){
                            $area=$db->prepare("SELECT * FROM areacnpq where nome_areacnpq=?");
                            $areacnpq=$_GET['areacnpq'];// echo $areacnpq;
                            $area->bindValue(1, $areacnpq, PDO::PARAM_STR);
                            $area->execute();
                            $linhas = $area->fetchAll(PDO::FETCH_ASSOC);
                            if($area->rowCount() > 0){
                                // header("Location:tela_org.php");
                                echo"<script>alert('Area CNPQ já cadastrada');</script>"; 
                            }
                            else
                            {
                                $a = $db->prepare("INSERT INTO areacnpq (nome_areacnpq) VALUES(:nome_areacnpq)");
                                $a-> execute(array(':nome_areacnpq'=>$areacnpq));
                                echo "<script>alert('Adicionado');</script>";
                            }
                        }
                    ?>
                <input type="submit" name="Add"  >
            </form>
        </fieldset><br>
    		
    	<fieldset><legend>Avaliar Aceitação</legend>
    		<form action="avaliados.php" method="GET" onsubmit="return verificanota();">
        		<label for="notaCorte">Selecionar trabalhos com notas acima de :</label>
        		<input type="number" id="notaCorte" step=0.1 name="notaCorte" min="1" max="8" placeholder="Min=1 e Máx=8">
        		<input type="submit" value="Selecionar">
			</form>
		</fieldset><br>

        <a href="atribuirResumo.php">Atribuir resumos</a><br><br>
        
        <a href="login.php?logout=1">Encerrar Sessão</a>
    </body>
</html>


