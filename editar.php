<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Editando Foto!</title>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="style.css?c=8" rel="stylesheet">
    </head>
 
<body>
<div class="container" align="center">
	<?php
		//teste2
		// Conexão com o banco de dados
		$conn = @mysql_connect("localhost", "root", "") or die ("Problemas na conexão.");
		$db = @mysql_select_db("estudo1", $conn) or die ("Problemas na conexão");
		
		$id = $_GET['id2'];
		// Seleciona a foto
		$sql = mysql_query("SELECT `foto` FROM usuarios WHERE id LIKE '%$id%' ");

		// Exibe as informações de cada usuário
		while ($usuario = mysql_fetch_object($sql)) {
			// Exibimos a foto
			echo "<br><img src='fotos/".$usuario->foto."' alt='Foto de exibição' /><br /><br>";
			echo"
				<form action=".$_SERVER['PHP_SELF']."?id2=$id method=\"post\" enctype=\"multipart/form-data\" name=\"cadastro\" >
					Foto de exibição:<br />
					<input type=\"file\" name=\"foto\" class=\"btn btn-lg btn-primary\"/>
					<input type=\"submit\" onclick=\"return confirm('Deseja mesmo editar esse registro?');\" name=\"Submit\" value=\"SALVAR ALTERAÇÕES\" class=\"btn btn-lg btn-primary\"/>
				</form>
			";
		}

		// Se o usuário clicou no botão cadastrar efetua as ações
		if (isset($_POST['Submit'])) {

			$sql3 = mysql_query("SELECT `foto` FROM usuarios WHERE id LIKE '%$id%' ");
			$usuario2 = mysql_fetch_object($sql3);
			
			$file_name = $usuario2->foto;
			$filedel = "C:/wamp64/www/estudo_foto_php/fotos/".$file_name;
			unlink($filedel);

			// Recupera os dados dos campos
			$foto = $_FILES["foto"];
			
			// Se a foto estiver sido selecionada
			if (!empty($foto["name"])) {
				
				// Largura máxima em pixels
				$largura = 1200;
				// Altura máxima em pixels
				$altura = 1200;
				// Tamanho máximo do arquivo em bytes
				$tamanho = 100000020000;
		
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
					$sql2 = mysql_query("UPDATE usuarios SET foto ='$nome_imagem' WHERE usuarios.id LIKE '%$id%'");
				
					// Se os dados forem inseridos com sucesso
					if ($sql2){
						echo "<br>Imagem Atualizada com Sucesso!";
						header("Location: index.php");
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
</div>

</body>
</html>
