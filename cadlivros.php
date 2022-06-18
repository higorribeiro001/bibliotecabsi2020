<?php
require('bd/conection.php'); // conexao

$erroArquivo = ""; //assume erro
$erroAutor = ""; //assume erro

session_start(); //iniciar sessao

if(!isset($_SESSION['nome'])) { //se nao existir dados na sessao com nome do usuario 
    $usuario = "Desconhecido"; //nome de usuario
} else { //se nao
    $usuario = $_SESSION['nome']; //nome de usuario sera igual ao nome que esta na sessao
}

$disciplina = $_SESSION['disciplina']; //variavel disciplina assume o dado guardado na sessao

if($_SERVER['REQUEST_METHOD']=='POST') { // se existir metodo post
    if(!isset($_POST['autor'])) { //se nao existir dados na caixa de texto autor
        $erroAutor = "Por favor, digite o nome do autor!"; //erro
    } else { //se nao
        $autor = $_POST['autor']; //variavel autor assume o que estiver digitado
    }
   
    $arquivo = $_FILES['livro']; //se existir aquivo no input file, a variavel arquivo assume este dado
    //echo var_dump($_FILES['livro']);

    $pasta = "arquivos/"; //pasta
    $nomearquivo = $arquivo['name']; //nome do arquivo
    $novoNomeArquivo = $nomearquivo; //nova variavel para o nome do arquivo
    $extensao = strtolower(pathinfo($nomearquivo, PATHINFO_EXTENSION)); //assume o tipo/extensao do arquivo

    if($arquivo['error']) { //ocorrer erro
        $erroArquivo = ("Falha ao enviar o arquivo!"); //erro
    } else if($arquivo['size'] > 40097152) { //se arquivo for maior que 40 MB
        $erroArquivo = ("Erro! Só é permitido até 40 MB!"); //erro
    } else if($extensao != "pdf") { //se o tipo do arquivo nao for pdf
        $erroArquivo = ("Tipo de arquivo não aceito!"); //erro
    } else { //se nao ocorrer nada disso, é feito o registro do livro no bd e seu envio para a pasta
        $upload = move_uploaded_file($arquivo["tmp_name"], $pasta.$novoNomeArquivo.".".$extensao); //upload do arquivo
        if($upload) { //se o upload ocorrer
            echo "Livro enviado com sucesso!"; //retorno
        }

        $nomeArquivoparabd = $novoNomeArquivo.".".$extensao; // variavel que assume o novo nome do arquivo que foi registrado no bd

        $sql = $pdo->prepare("INSERT INTO livros VALUES (null, ?, ?, ?, ?)"); //insere dados no bd
        $sql->execute(array($nomeArquivoparabd, $autor, $usuario, $disciplina)); //seleciona os objetos nas variaveis e lanca num array
        echo "<p><b>Cadastro realizado com sucesso!</b></p>"; //retorno
    }
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
    <form action="" method="post" enctype="multipart/form-data">
        <nav>
            <img src="imagens/fundobiblioteca.jpg" alt="">
        </nav>
        <header>
            <ul>
                <li><a href="login.php"><?php echo $usuario ?></a></li>
            </ul>
        </header>
        <br><br><br><br><header>
            <main2>
                <br><h2>Cadastro de livro:</h2>
                <input type="file" name="livro" required><br>
                <span class="erro"><?php echo $erroArquivo; ?></span><br>
                <label>Autor:</label>
                <input type="text" name="autor" placeholder="Digite o nome do autor"><br>
                <span class="erro"><?php echo $erroAutor; ?></span><br>
                <button name="cad_livros" type="submit">Cadastrar</button><br><br>
            <br></main2>
        </header>
        <?php
        if(isset($_POST['cad_livros']) & isset($_POST['arquivo']) & isset($_POST['autor'])) { //se o botao cad_livros for acionado, e existir dados em arquivo e nome do autor
            $nomeArquivoparabd = $novoNomeArquivo.".".$extensao; //novo nome do arquivo
            $autor = $_POST['autor']; //nome do autor é igual ao que foi digitado
            $disciplina = $_SESSION['disciplina']; //disciplina sera igual ao q esta guardado na sessao
            $arquivo = $_FILES['arquivo']; //o arquivo sera o que esta no input file
            $usuario = $_SESSION['nome']; //o nome de usuario sera igual ao que esta guardado na sessao
        } 
        ?>
    </form>
    <br><br><br><br><br><br><footer>
        &copy; sistemas de informação | 2020-2022
    </footer>
</body>
</html>