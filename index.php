
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Cadastro de Fotos</title>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="style.css?c=8" rel="stylesheet">
    </head>
 
    <body>
		<div class="container" align="left">
			<h1 class="title">Cadastro de Imagens</h1>
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" name="cadastro" >
					Foto de exibição:<br />
					<input type="file" name="foto" class="btn btn-lg btn-primary"/><br/>
					<input type="submit" name="cadastrar" value="Cadastrar Foto" class="btn btn-lg btn-primary"/>
				</form>

		<br>
		<br>

			<?php
				$conn = @mysql_connect("localhost", "root", "") or die ("Problemas na conexão.");
				$db = @mysql_select_db("estudo1", $conn) or die ("Problemas na conexão");			

				// Seleciona todos os usuários
				$sql = mysql_query("SELECT * FROM usuarios ORDER BY id DESC");
					
				// Exibe as informações de cada usuário
				while ($usuario = mysql_fetch_object($sql)) {

					// Exibimos a foto
					echo "<div class=\"img-block col-md-2\" align=\"center\">";
							echo "<br><img class=\"exibition\" align=\"center\" src='fotos/".$usuario->foto."' alt='Foto de exibição' /><br />";
							echo "<br><a class=\"btn btn-lg btn-primary\" href=\"editar.php?id2=$usuario->id\">Editar Foto</a><br>";
							echo "<a class=\"btn btn-lg btn-primary\" href=\"excluir.php?id2=$usuario->id\">Excluir Foto</a><br>";
					echo "</div>";
				}
			?>
			<br>
			<br>
		</div>
    </body>

	<script src="js/bootstrap.min.js"></script>

	<footer>
		<div class="container" align="center">
			<h5> Desenvolvido por Pablo Santos </h5>
		</div>
	</footer>

</html>

<?php

// Conexão com o banco de dados
$conn = @mysql_connect("localhost", "root", "") or die ("Problemas na conexão.");
$db = @mysql_select_db("estudo1", $conn) or die ("Problemas na conexão");

// Se o usuário clicou no botão cadastrar efetua as ações
if (isset($_POST['cadastrar'])) {
	
	// Recupera os dados dos campos
	$foto = $_FILES["foto"];
	
	// Se a foto estiver sido selecionada
	if (!empty($foto["name"])) {
		
		// Largura máxima em pixels
		$largura = 1200;
		// Altura máxima em pixels
		$altura = 1200;
		// Tamanho máximo do arquivo em bytes
		$tamanho = 100000;
 
		$error = array();
 
    	// Verifica se o arquivo é uma imagem
    	if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"])){
     	   $error[1] = "Isso não é uma imagem.";
   	 	} 
	
		// Pega as dimensões da imagem
		$dimensoes = getimagesize($foto["tmp_name"]);
	
		// Verifica se a largura da imagem é maior que a largura permitida
		if($dimensoes[0] > $largura) {
			$error[2] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
		}

		// Verifica se a altura da imagem é maior que a altura permitida
		if($dimensoes[1] > $altura) {
			$error[3] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
		}
		
		// Verifica se o tamanho da imagem é maior que o tamanho permitido
		if($foto["size"] > $tamanho) {
   		 	$error[4] = "A imagem deve ter no máximo ".$tamanho." bytes";
		}
 
		// Se não houver nenhum erro
		if (count($error) == 0) {
		
			// Pega extensão da imagem
			preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
 
        	// Gera um nome único para a imagem
        	$nome_imagem = md5(uniqid(time())) . "." . $ext[1];
 
        	// Caminho de onde ficará a imagem
        	$caminho_imagem = "fotos/" . $nome_imagem;
 
			// Faz o upload da imagem para seu respectivo caminho
			move_uploaded_file($foto["tmp_name"], $caminho_imagem);
		
			// Insere os dados no banco
			$sql = mysql_query("INSERT INTO usuarios(nome, email, foto) VALUES ('".$nome."', '".$email."', '".$nome_imagem."')");
		
			// Se os dados forem inseridos com sucesso
			if ($sql){
				echo "Você foi cadastrado com sucesso.";
				header('location: ' . dirname( $_SERVER['PHP_SELF'] ) . '/index.php');
			}
		}
	
		// Se houver mensagens de erro, exibe-as
		if (count($error) != 0) {
			foreach ($error as $erro) {
				echo $erro . "<br />";
			}
		}
	}
}

?>

