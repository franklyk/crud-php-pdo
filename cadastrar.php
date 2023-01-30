<?php
    ob_start();
    session_start();
    include_once "./conexao.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
</head>
<body>
    <a href="index.php">Listar</a>
    <h1>Cadastrar</h1>
    <?php
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    

    if(!empty($dados['CadUsuario'])){
        $empty_input = false;

        $dados = array_map('trim', $dados);

        if(in_array("", $dados)){
            $empty_input = true;
            echo "<p style='color: red' >É necessário preencher todos os campos!!</p>";
        }else if(!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $empty_input = true;
            echo "<p style='color: red' >É necessário preencher com e-mail válido!!</p>";
        }

        // var_dump($dados);

        if(!$empty_input){
            $query_usuario = "INSERT INTO usuarios (nome, email) VALUES (:nome, :email)";
            $cad_usuario = $conn->prepare($query_usuario);
            $cad_usuario-> bindParam(':nome', $dados['nome']);
            $cad_usuario-> bindParam(':email', $dados['email']);
            $cad_usuario->execute();

            if($cad_usuario->rowCount()){
                unset($dados);
                $_SESSION['msg'] = "<p style='color: green' >Usuário cadastrado com sucesso!</p>";
                header('location: index.php');
            }else{
                echo "<p style='color: red' >Erro: Usuário não cadastrado com sucesso!!</p>";
            }
        }
        
    }
    ?>
    <form name="cad-usuario" method="POST" action="">
        <label for="">Nome: </label>
        <input type="text" name="nome" id="nome" placeholder="Nome Completo" value="<?php 
        if(isset($dados['nome'])) {
            echo $dados['nome'];
            } 
            ?>"><br><br>

        <label for="">E-mail: </label>
        <input type="email" name="email" id="email" placeholder="Seu melhor E-mail " value="<?php 
        if(isset($dados['email'])) {
            echo $dados['email'];
            } 
            ?>"><br><br>

        <input type="submit" value="Cadastrar" name="CadUsuario">
    </form>
</body>
</html>