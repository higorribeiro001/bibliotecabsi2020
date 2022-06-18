<?php
require("bd/conection.php"); //conexao

$erroEmail = ""; //assume casos de erro
$erroSenha = "";//assume casos de erro

$email = ""; //assume casos de erro

$usuario = ""; //assume casos de erro

session_start(); //inicia sessao

if(!isset($_SESSION['nome'])) { //se a sessao para o nome do usuario não existir
    $usuario = "Desconhecido"; //usuario assume como desconhecido
} else {
    $usuario = $_SESSION['nome']; //se não, usuario assume como o que tiver na sessao
}

if($_SERVER['REQUEST_METHOD']=='POST') { //se o metodo post existir
    if(isset($_POST['login'])) { //se o botao login existir ou for pressionado
        if(empty($_POST['email']) || empty($_POST['senha'])) { //se a caixa de email estiver vazia
            $erroEmail = "Por favor, digite seu e-mail!"; //assume erro
            $erroSenha = "Por favor, digite sua senha!"; //assume erro
        } else {
            $email = $_POST['email']; //email assume o que há na caixa input para email
        }
    }
}

$sql = $pdo->prepare("SELECT *FROM cadastrados WHERE Email=?"); //seleciona valores da tabela atraves da variavel disciplina
$sql->execute(array($email)); //coleta a variavel email
$dados = $sql->fetchAll(); //dados assume arrays com os dados da tabela
    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Online BSI</title>
    <link rel="stylesheet" href="estilo1.css">
    <link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon">
</head>
<body>
    <form action="" method="post">
        <nav>
            <img src="imagens/fundobiblioteca.jpg" alt="">
        </nav>
        <header>
            <ul>
                <li><a href="login.php"><?php echo $usuario ?></a></li>
            </ul>
        </header>
        <br><br><header>
            <main2>
                <br><h2>Login</h2>
                <label>E-mail:</label>

                <input type="email" placeholder="Digite seu e-mail" name="email" <?php if(!empty($erroEmail)) { echo "class='invalido'"; } ?>require><br>
                <span class="erro"><?php echo $erroEmail; ?></span><br>

                <label">Senha:</label>
                <input type="password" name="senha" placeholder="Digite sua senha" <?php if(!empty($erroSenha)) { echo "class='invalido'"; } ?>require><br>
                <span class="erro"><?php echo $erroSenha; ?></span><br>

                <button type="submit" name="login">Confirmar</button>
                <p>Não é cadastrado? <a href="cadastrar.php"><u>Cadastrar</u></a> </p><br>
                
            </main2>
        </header>
        <?php
        if(isset($_POST['login']) & isset($_POST['email']) &isset($_POST['senha'])) { //se botao login, caixa de email e senha existirem dados
            $senha = $_POST['senha']; //senha assume o dado que a caixa de senha tem
            foreach($dados as $chave => $valor) { //for para array ira passar chave em dadaos, valor irá assumir cada valor especifico
                if($senha!=$valor['Senha']) { //se a senha digitada for diferente da senha que foi registrada no banco de dados
                    echo "<br><p>E-mail e/ou senha inválido(s)!</p><br>"; //retorno
                } else { // se nao, usuario é logado
                    echo "<br><p>Login realizado ocm sucesso!</p><br>";
                    session_start(); //inicia sessao

                    $_SESSION['nome'] = $valor['Nome']; //guarda o nome do usuario 

                    header('location: index.php'); //volta a tela principal
                }
            }
        }
        ?>
    </form>
    <br><br><br><br><footer>
        &copy; sistemas de informação | 2020-2022
    </footer>
</body>
</html>