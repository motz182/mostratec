<?php
	if (isset($_POST['areaCNPQ'])){
		if($_POST['areaCNPQ']=="1") {
      header("Location:formCadastro.php"); 
    }
}
	$db = new PDO("pgsql:host=localhost;dbname=mostratec;port=5432",'postgres','postgres');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if(!empty($_POST['nome'] && $_POST['email'] && $_POST['areaCNPQ'] && $_POST['senha'])){

		$r = $db->prepare('SELECT * FROM autor WHERE email=? ');//faz select para verificar se o email ja esta cadastrado
		$email = $_POST['email'];

		$r->bindValue(1, $email, PDO::PARAM_STR);
		$r->execute();
		$linhas = $r->fetchAll(PDO::FETCH_ASSOC);

		if($r->rowCount() > 0){//feito pelo professor se nao encontrou nenhuma linha que tem aque email
			echo "Essa pessoa ja esta cadastrada!";
			echo "<br>";			
		}
		else 
		{
			$nome = $_POST['nome'];
			$email = $_POST['email'];
			$areaCNPQ=$_POST['areaCNPQ'];
			$senha = $_POST['senha'];

			$r = $db->prepare("INSERT INTO autor(nome,email,areaCNPQ,senha) VALUES(:nome,:email,:areaCNPQ,:senha)");
			$r->execute(array(':nome' => $nome, ':email' => $email, ':areaCNPQ'=>$areaCNPQ,':senha' => $senha));
			echo "dados inseridos";
			header("Location:login.php");
		}
	} 
	else
	{
		echo "vc deixou campos vazios";
	}
?>