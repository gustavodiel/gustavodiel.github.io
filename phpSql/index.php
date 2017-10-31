<html>

<head>
    <title>PHP + mySQL</title>
</head>

<body>
<center>
    <h1>Hello!</h1>
    <?php

    $connect = mysqli_connect("localhost", "root", "");
    if (!$connect){
        die("Não consegui conectar! ".mysqli_error());
    }

    if (!mysqli_select_db($connect, "odaw")){
        echo("Falha ao usar Database! Criando um novo. ".mysqli_error($connect));
        mysqli_query($connect, "CREATE DATABASE odaw");
        if (!mysqli_select_db($connect, "odaw")){
            die("Falha ao usar Database! ".mysqli_error($connect));
        }
    }

    echo("Conectei!<br>");
    if ($resultado = mysqli_query($connect, "SELECT * FROM aluno", MYSQLI_ASSOC)){
        while ($linha = mysqli_fetch_array($resultado)){
            echo $linha['nome']." - ".$linha['email']."<br>";
        }
    } else {
        echo "Falha!<br>".mysqli_error($connect);
    }


    mysqli_close($connect);

    ?>
</center>
</body>

</html>