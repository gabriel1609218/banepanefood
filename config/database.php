<?php
$host='localhost';
$user='root';
$pass='';
$db='banepanefoods';
$conn=new mysqli($host,$user,$pass,$db);
if($conn->connect_error) {
    http_response_code(500);
    die('Erro na conexão com o banco de dados: '.$conn->connect_error);
}
$conn->set_charset('utf8mb4');
?>
