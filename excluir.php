<?php
    $conn = @mysql_connect("localhost", "root", "") or die ("Problemas na conexão.");
    $db = @mysql_select_db("estudo1", $conn) or die ("Problemas na conexão");
    
    // ID de exemplo
    $id = $_GET['id2'];

    //Removendo a foto
    $sql3 = mysql_query("SELECT `foto` FROM usuarios WHERE id LIKE '%$id%' ");
    $usuario2 = mysql_fetch_object($sql3);
    echo $usuario2->foto;
    
    $file_name = $usuario2->foto;
    $filedel = "C:/wamp64/www/estudo_foto_php/fotos/".$file_name;
    unlink($filedel);
    
    // Removendo usuário do banco de dados
    $sql2 = mysql_query("DELETE FROM usuarios WHERE id = '".$id."'");
    header("Location: index.php");
?>