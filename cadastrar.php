<?php
require('bd/conection.php'); //conexao ao bd

$erroNome = ""; //assume erro
$erroEmail = ""; //assume erro
$erroSenha = ""; //assume erro
$errocon_senha = ""; //assume erro

if($_SERVER['REQUEST_METHOD']=='POST') { //se metodo post existir
    if(empty($_POST['nome'])) { // se a caixa de nome estiver vazio
        $erroNome = "Por favor, digite seu nome!"; //da erro
    } else if(!preg_match("/^[a-zA-Z-' ]*$/", $_POST['nome'])) { //se nao, preg_match negado so deixa passar letras maiusculas, minusculas e espaços em branco
        $erroNome = "Aceitamos apenas letras!"; //erro
    } else { //se nao, apos passar a variavel nome assume o nome digitado na caixa
        $nome = $_POST['nome'];
    }

    if(empty($_POST['email'])) { //se caixa do email estiver vazia
        $erroEmail = "Por favor, digite seu email!"; //erro
    } else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { // verifica email, como aqui está negado, quer dizer que se o amil nao for valido
        $erroEmail = "Email inválido!"; // da erro
    } else { //se nao, aqui a variavel email assume
        $email = $_POST['email']; //variavel email assume o que há digitado na caixa de email
    }

    if(empty($_POST['senha'])) { //se a senha estiver vazia
        $erroSenha = "Por favor, digite sua senha!"; //erro
    } else if(empty($_POST['con_senha'])) { //caixa de texto de confirmar senha
        $erroRe_Senha = "Por favor, digite sua senha!"; //erro
    } else if(strlen($_POST['senha']) < 6) { // se tiver menos de 6 caracteres na senha
        $erroSenha = "Senha deve ter no mínimo 6 caracteres!"; //erro
    } else if($_POST['con_senha']!=$_POST['senha']) { //se a senha de confirmação for diferente da senha digitada antes
        $erroRe_Senha = "Esta senha não coincide com a criada!"; //erro
    } else { //se nao, ta tudo ok
        $senha = $_POST['senha']; //variavel senha assume o que há na caixa de texto senha
    }

}


$usuario = "";

session_start();

if(!isset($_SESSION['nome'])) {
    $usuario = "Desconhecido";
} else {
    $usuario = $_SESSION['nome'];
}
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
                <br><h2>Cadastro:</h2>
                <label>Nome:</label>
                <input type="text" name="nome" placeholder="Digite seu nome"><br>
                <span class="erro"><?php echo $erroNome; ?></span><br>
                <label>E-mail:</label>
                <input type="email" name="email" placeholder="Digite seu e-mail"><br>
                <span class="erro"><?php echo $erroEmail; ?></span><br>
                <label>Senha:</label>
                <input type="password" name="senha" placeholder="Digite sua senha"><br>
                <span class="erro"><?php echo $erroSenha; ?></span><br>
                <label>Confirmar Senha:</label>
                <input type="password" name="con_senha" placeholder="Digite novamente sua senha"><br>
                <span class="erro"><?php $errocon_senha; ?></span><br>

                <button type="submit" name="cad">Cadastrar</button><br><br>
            <br></main2>
        </header>
        <?php
        if(isset($_POST['cad']) & !empty($_POST['nome']) & !empty($_POST['email']) & !empty($_POST['senha']) & !empty($_POST['con_senha'])) { //se o botao cad for assionado, tiver dados nas caixas de nome, email e senha
            $sql = $pdo->prepare("INSERT INTO cadastrados VALUES (null, ?, ?,?)"); //insere esses dados no bd
            $sql->execute(array($nome,$email,$senha)); //dados coletados sao colocados no array para ser executado o registro
            echo "<br><p><b>Cadastro realizado com sucesso!</b></p><br"; //retorno

            session_start(); //inicia sessao
            $_SESSION['nome'] = $_POST['nome']; //guarda o nome do usuario
            header('location: index.php'); //retorna a tela principal
        }
        ?>
    </form><br><br>
    <br><br><footer>
        &copy; sistemas de informação | 2020-2022
    </footer>
</body>
</html>