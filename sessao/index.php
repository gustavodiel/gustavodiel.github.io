<html>
    <?php
        function alerta($msg){
            echo "<script type='text/javascript'>alert('".$msg."')</script>";
        }
        $resposta = "";

        if (!empty($_POST["login"])){
            $pw = $_POST["pw"];
            $nome = $_POST["nome"];
            $connect = mysqli_connect("localhost", "root", "", "odaw");
            if (!$connect){
                die("Não consegui conectar! ".mysqli_error($connect));
            }
            $res = mysqli_query($connect, "SELECT * FROM aluno WHERE nome = '".$nome."';", MYSQLI_ASSOC);
            if (!$res){
                $resposta = "<font color=#ff0000>Usuário não existe!!!!!!!</font>";
                die(mysqli_error($connect));
            }else {
                $aluno = mysqli_fetch_assoc($res);

                $res->close();

                if ($aluno["senha"] == $pw){
                    echo($aluno["nome"]);
                    echo("<h1>Login com Sucesso!!</h1><br>Redirecionando em 3 segundos...<hr>");
                    session_start();
                    $_SESSION["usuario"] = $aluno;
                    header("Refresh: 3");
                    return;
                } else {
                    $resposta = "<font color=#ff0000>Senha ou Usuário não existem!</font>";
                }

            }

            mysqli_close($connect);
            
        } else if (!empty($_POST["logout"])){
            session_start();
            unset($_SESSION["usuario"]);
            echo("<h1>Tchau!!</h1><br>Redirecionando em 3 segundos...<hr>");
            header("Refresh: 3");
            return;
        } else {
            session_start();
            if (isset($_SESSION["usuario"])){
                $aluno = $_SESSION["usuario"];
                if ($aluno){
                    echo("Bem vindo devolta, ".$aluno["nome"]."!<br>");
                    echo('<form action="index.php" method="post"><button type="submit" name="logout" value="Submit">Logout</button></form>');
                    return;
                }
            }
        }
    ?>
    <head>
        <meta charset="utf-8">
        <title>Teste de Login e Sessão PHP + SQL - Gustavo Diel</title>
    </head>
    <body bgcolor="#ccddff">
        <h1>Login e Sessão</h1>
        <form action="index.php" method="post">
            Seu nome: <input type="text" name="nome" id="nome"> <?php echo($resposta) ?><br>
            Sua senha: <input type="password" name="pw" id="pw"><br>
            <button type="submit" name="login" value="Submit">Login</button>

        </form>
    </body>
</html>