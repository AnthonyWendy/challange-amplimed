<?php

include "./lib/lib.php";

$connMysql = mysqli_connect("database", "root", "root", "MedicalChallenge")
  or die("Não foi possível conectar ao servidor MySQL: MedicalChallenge\n");

$connMysql->set_charset("utf8");


$connMaria = mysqli_connect("mariadb", "root", "root", "0temp")
  or die("Não foi possível conectar ao servidor MariaDB: 0temp\n");


echo "Início da Migração: " . dateNow() . ".<br>";

criandoEstruturaMariaDB($connMaria);

inserirDadosNoMariaDb($connMaria);

inserirDadosNoMysql($connMysql, $connMaria);

echo "<br>--------------------------------------------inicio-CriaçãoDeBackup--------------------------------------------------------<br>";
echo gerarDumpMysql();
echo "<br>--------------------------------------------fim-CriaçãoDeBackup--------------------------------------------------------<br>";


echo("\n anthony");

// Fechar conexões
mysqli_close($connMysql);
mysqli_close($connMaria);
?>
