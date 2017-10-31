<html>
    <?php
        function alerta($msg){
            echo "<script type='text/javascript'>alert('".$msg."')</script>";
        }
        function isValid(&$msg){
            $nome = $_POST["nome"];
            if ($nome == ""){
                $msg = "Voce deve inserir um nome!!!";
                return;
            }
            if ($_POST["pw"] == ""){
                $msg = "Por favor, digite uma senha!";
                return false;
            }
            if (strlen($_POST["pw"]) < 5){
                $msg = "Senha muito curta! Mínimo de 5 (cinco) caracteres!";
                return false;
            }
            if (!isset($_POST["leu"])){
                $msg = "Por favor, diga se aceita :)";
                return false;
            }
            return true;
        }
        function getValorSelecao(){
            $sel = $_POST["leu"];
            if ($sel == "sim")
                return 1;
            if ($sel == "nao")
                return 2;
            if ($sel == "obvio")
                return 3;
            return 4;
        }
        if (!empty($_POST["inserir"])){
            $pw = $_POST["pw"];
            $msg;
            if (!isValid($msg)){
                alerta($msg);
            } else {
                $connect = mysqli_connect("localhost", "root", "", "odaw");
                if (!$connect){
                    die("Não consegui conectar! ".mysqli_error());
                }

                $res = mysqli_query($connect, "SELECT COUNT(1) AS total FROM aluno;", MYSQLI_ASSOC);

                $quantidadeAlunos = mysqli_fetch_assoc($res);

                $res->close();
                $num = isset($_POST['idade']) ? 1 : 0;
               
                $query = "INSERT INTO aluno VALUES (".($quantidadeAlunos['total'] + 1).", '".$_POST["nome"]."', '".$_POST["pw"]."',".$num.", '".$_POST["comida"]."', ".getValorSelecao()." );";
                echo $query;
                if (!$resultado = mysqli_query($connect, $query, MYSQLI_ASSOC)){
                    echo "Falha!<br>".mysqli_error($connect);
                    mysqli_close($connect);
                    return;
                }

                echo("<h1>Inserido!</h1><hr>");

                mysqli_close($connect);
            }
        }
        if (!empty($_POST["atualizar"])){

            $connect = mysqli_connect("localhost", "root", "", "odaw");
            if (!$connect){
                die("Não consegui conectar! ".mysqli_error());
            }
            $array = array();
            if ($resultado = mysqli_query($connect, "SELECT * FROM aluno", MYSQLI_ASSOC)){
                
                
                while ($linha = mysqli_fetch_array($resultado, MYSQLI_ASSOC)){
                    $cod = $linha['cod'];
                    $num = isset($_POST['idade'.$cod]) ? 1 : 0;
                    
                    $array[] = "UPDATE aluno SET nome='".$_POST['nome'.$cod]."', maior=".$num.", comida='".$_POST['comida'.$cod]."' WHERE cod = ".$cod.";";

                }
                $resultado->close();

            }   
            foreach ($array as $query) {
                if (!mysqli_query($connect, $query, MYSQLI_ASSOC)){
                    echo mysqli_error($connect);
                }
            }
            print "<h1>Atualizado!</h1>";
        }
        if (!empty($_POST["excluir"])){
            $total = 0;
            $connect = mysqli_connect("localhost", "root", "", "odaw");
            if (!$connect){
                die("Não consegui conectar! ".mysqli_error());
            }
            $array = "(";
            if ($resultado = mysqli_query($connect, "SELECT * FROM aluno", MYSQLI_ASSOC)){
                
                
                while ($linha = mysqli_fetch_array($resultado, MYSQLI_ASSOC)){
                    $cod = $linha['cod'];
                    if (isset($_POST['excluir'.$cod])){
                        $array = $array.$cod.',';
                        $total = $total + 1;
                    }
                }
                $array = rtrim($array, ",").")";
                $resultado->close();

            }
            if ($total != 0){
                if (!mysqli_query($connect, "DELETE FROM aluno WHERE cod in ".$array.";", MYSQLI_ASSOC)){
                    echo mysqli_error($connect);
                }
                print "<h1>Excluido!</h1>";
            }
        }
    ?>
    <head>
        <meta charset="utf-8">
        <title>Teste de Formulário PHP + SQL - Gustavo Diel</title>
    </head>
    <body bgcolor="#ccddff">
        <h1>Inserção</h1>
        <form action="index.php" method="post">
            Nome do Aluno: <input type="text" name="nome" id="nome"><br>
            Senha do Aluno: <input type="password" name="pw" id="pw"><br>
            Ele é maior de idade? <input type="checkbox" name="idade" id="idade"> Sim
            <br>
            <hr>
            Selecione uma comida:
            <select name="comida">
                <option value="macarrao">Macarrão</option>
                <option value="pizza">Pizza</option>
                <option value="banana">🍌</option>
                <option value="milho">milho</option>
                <option value="bambaga" selected>🍔</option>
            </select>
            <br><br>
            Este site é extremamente bem feito. Você concorda com a afirmação?<br>
            <input type="radio" name="leu" value="sim"> Sim<br>
            <input type="radio" name="leu" value="nao"> Não<br>
            <input type="radio" name="leu" value="other"> Talvez<br>
            <input type="radio" name="leu" value="obvio"> É óbvio que não<br>

            <hr>

            Clique onde a sua resposta for <strong>SIM</strong><br>
            <button type="reset" value="Reset">Se arrependeu?</button>
            <button type="submit" name="inserir" value="Submit">Quer inserir?</button>

        </form>

        <hr>
        <h1>Lista de coitados:</h1>

        <form action="index.php" method="post">
            <?php
                $connect = mysqli_connect("localhost", "root", "", "odaw");
                if (!$connect){
                    die("Não consegui conectar! ".mysqli_error());
                }
                print "<table border='2'>";
                if ($resultado = mysqli_query($connect, "SELECT * FROM aluno", MYSQLI_ASSOC)){
                    print "<tr><th>ID</th><th>Nome</th><th>Senha</th><th>Ele é de maior?</th><th>Comida Preferida</th><th>Como ele concordou com o termo?</th><th>Excluir?</th></tr>";
                    while ($linha = mysqli_fetch_array($resultado)){
                        $codigo = "<td align='center'>".$linha['cod']."</td>";
                        $nome = "<td align='center'><input type='text' name='nome".$linha['cod']."' value='".$linha['nome']."'></td>";
                        $senha = "<td align='center'>".$linha['senha']."</td>";
                        $idade = "<td align='center'><input type='checkbox' name='idade".$linha['cod']."' ".($linha['maior'] == 1 ? 'checked' : '')." ></td>";
                        $list = '<td align="center"><select name="comida'.$linha['cod'].'"><option value="macarrao"'.($linha['comida'] == 'macarrao' ? 'selected' : '').'>Macarrão</option><option value="pizza"'.($linha['comida'] == 'pizza' ? 'selected' : '').'>Pizza</option><option value="banana"'.($linha['comida'] == 'banana' ? 'selected' : '').'>🍌</option><option value="milho"'.($linha['comida'] == 'milho' ? 'selected' : '').'>milho</option><option value="bambaga" '.($linha['comida'] == 'bambaga' ? 'selected' : '').'>🍔</option></select></td>';
                        $concorda = "<td align='center'>".$linha['concorda']."</td>";
                        $deletar = "<td align='center'><input type='checkbox' name='excluir".$linha['cod']."'/>";
                        print  "<tr>".$codigo.$nome.$senha.$idade.$list.$concorda.$deletar."</tr>";
                    }
                } else {
                    echo "Falha!<br>".mysqli_error($connect);
                }
                print "</table>";
                mysqli_close($connect);
            ?>
            <button type="submit" name="atualizar" value="Submit">Partiu atualizar os dados?</button>
            <button type="submit" name="excluir" value="Excluir">Partiu excluir os dados selecionados?</button>
        </form>
    </body>
</html>