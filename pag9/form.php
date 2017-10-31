<html>
<body>

<?php

function isValid($usuario, $senha, &$msg){
    if ($senha == ""){
        $msg = "Por favor, digite uma senha!";
        return false;
    }
    if (strlen($senha) < 5){
        $msg = "Senha muito curta! Mínimo de 5 (cinco) caracteres!";
        return false;
    }

    $fileName = "autorizacoes.txt";

    if (isset($_POST["novo"])){
        $hashedPw = password_hash($senha, PASSWORD_DEFAULT);
        $file = fopen($fileName, "a+");
        fprintf($file, "%s %s\n", $usuario, $hashedPw);
        fclose($file);

    } else {
        $lines = file($fileName);
        $foi = false;
        foreach($lines as $line){
            list($nome, $pw) = sscanf($line, "%s %s");
            if ($nome == $usuario && password_verify($senha, $pw)){
                $foi = true;
            }
        }
        
        if (!$foi){
            $msg = "Usuário ou senha não existem!";
            return false;
        }
    }



    return true;
}

$nome = $_POST["nome"];
if ($nome == ""){
    echo("Voce deve inserir um nome!!!");
    return;
}

$pw = $_POST["pw"];
$msg;
if (!isValid($nome, $pw, $msg)){
    echo($msg);
    return;
}

$perso = $_POST["perso"];
if (strlen($perso) < 4){
    echo("Ei amigão, digita algo que presta por favor.");
    return;
}

$comida = $_POST["selLugares"];
if ($comida != "bambaga"){
    echo("Pessoas com mal gosto não passarão dessa parte!");
    return;
}

if(!isset($_POST['check'])){
    echo("Por favor, leia o contrato de usuário final!");
    return;
}

$leu = $_POST["leu"];
if ($leu != "sim" && $leu != "obvio"){
    echo("Na moral, nao precisa falar a verdade nessas coisas e.e");
    return;
}

?>


Bom dia <?php echo $nome; ?>!<br>
Sua senha é: <?php echo $pw; ?>, por favor, anote-a. Não esqueca ela!<br>
<hr>
O seu comentario da pagina foi o seguinte:<br>
<?php echo $perso; ?>
<hr>
<?php
    if ($leu == "sim"){
        echo("Voce leu o contrato!");
    } else {
        echo("Voce não leu o contrato, mas foi sincero!");
    }
?>


</body>
</html>