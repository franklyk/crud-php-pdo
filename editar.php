<?php
    session_start();
    ob_start();
    include_once "./conexao.php";

    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
    var_dump($id);

    if(empty($id)){
        $_SESSION['msg'] = "<p style='color: red;' >Erro! Usuário não encontrado!!</p>";
        header('location: index.php');
        exit();
    }

    $query_usuario = "SELECT id, nome, email FROM usuarios   WHERE id = $id LIMIT 1";
    $result_usuario = $conn->prepare($query_usuario);
    $result_usuario->execute();

    if(($result_usuario) AND ($result_usuario->rowCount() != 0 )){
        $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
        var_dump( $row_usuario);
    }else{
        $_SESSION['msg'] = "<p style='color: red;' >Erro! Usuário não encontrado!!</p>";
        header('location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar</title>
</head>
<body>
    <a href="index.php">Listar</a> <br>
    <a href="cadastrar.php">Cadastrar</a><br>
    <h1>Editar</h1>
    <?php
    //receber os dados do formulario
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
    //verificar se o usuario clicou no botão
    if(!empty($dados['EditUsuario'])){
        $empty_input = false;
        $dados = array_map('trim', $dados);

        if(in_array("", $dados)){

            $empty_input = true;
            echo "<p style='color: red' >É necessário preencher todos os campos!!</p>";
        }elseif(!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)){
            $empty_input = true;
            echo "<p style='color: red' >É necessário preencher com E-mail válido!!</p>";
        }




        if(!$empty_input){
            $query_up_usuario = "UPDATE usuarios SET nome= :nome, email= :email WHERE id = $id";
            $edit_usuario = $conn->prepare($query_up_usuario);
            
            $edit_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
            $edit_usuario->bindParam(':email', $dados['email'], PDO::PARAM_STR);
            // $edit_usuario->bindParam(':id', $id, PDO::PARAM_INT);
            if($edit_usuario->execute()){
                $_SESSION['msg'] = "<p style='color: green' >Usuário  editado com sucesso!!</p>";
                header('location: index.php');
            }else{echo "<p style='color: red' >Usuário não editado com sucesso!!</p>";
                
            }
        }
    }

    ?>

    <form id="edit-usuario" action="" method="POST">
        <label for="">Nome: </label>
        <input type="text" name="nome" id="nome" placeholder="Nome Completo"  value="<?php 
        if(isset($dados['nome'])) {
            echo $dados['nome'];
        }elseif(isset($row_usuario['nome'])){ echo $row_usuario['nome']; } ?>"><br><br>
        
        

        <label for="">E-mail: </label>
        <input type="email" name="email" id="email" placeholder="Melhor E-mail"  value="<?php 
        
        
        if(isset($dados['email'])) {
            echo $dados['email'];
        }elseif(isset($row_usuario['email'])){ echo $row_usuario['email']; } ?>"><br><br>

        <input type="submit" value="Salvar" name="EditUsuario">
    </form>
    
</body>
</html>