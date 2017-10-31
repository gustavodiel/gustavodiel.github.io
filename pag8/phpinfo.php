<?php

$data = date("d/m/Y");
echo("Hoje é ");
echo($data);
echo(". E agora são ");
echo(date("G"));
echo(":");
echo(date("i"));
echo("h");
echo("<br>");

##########


$fileName = "arquivo.txt";
$file = fopen($fileName, "r") or 0;
$num = 1;

if ($file == 0){
    echo("Nao existe!");
} else {
    fscanf($file, "%d", $num);
    $num = intval($num);
    $num = $num + 1;
    fclose($file);
}
$file = fopen($fileName, "w");
fprintf($file, "%d usuários passaram por aqui!", $num);
echo($num);
echo(" usuários passaram por aqui!");

?>
