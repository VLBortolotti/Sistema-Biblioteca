<?php include 'includes/db_connection.inc.php'; ?> <!-- CONNECTION TO DATABASE-->
<?php include 'includes/session.inc.php'; ?> <!-- STARTS A SESSION -->
<?php include 'includes/functions.inc.php'; ?> <!-- PHP FUNCTIONS -->
<?php include 'includes/check_if_logged.inc.php'; ?> <!-- REDIRECTS LOGGED USERS OUT OF THIS PAGE -->
<?php include 'includes/cadastrar.inc.php'; ?> <!-- THIS PAGE'S CODE -->

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastrar</title>
        <link rel="stylesheet" href="css/cadastroaluno.css">
    </head>

    <body id="tela_cadastro">
        <div class="form">

            <h1 class="cadastro-titulo">Cadastro de Alunos</h1>
            <form class="form-cadastro" action="cadastrar.php" method="post"> <!-- Formulario de cadastro para acessar o sistema -->
                <div class="firstname container">
                    <label for="firstname">Nome:</label>
                    <input type="text" placeholder="Insira o nome do aluno" name="firstname" id="firstname" value="<?php if(isset($_firstname)){echo $_firstname;}?>"><br>
                    <div><?php if(isset($error_firstname)){echo $error_firstname;}?></div>
                </div>
                <div class="lastname container">
                    <label for="lastname">Sobrenome:</label>
                    <input type="text" placeholder="Insira o sobrenome do aluno" name="lastname" id="lastname" value="<?php if(isset($_lastname)){echo $_lastname;}?>"><br>
                    <div><?php if(isset($error_lastname)){echo $error_lastname;}?></div>
                </div>
                <div class="email container">
                    <label for="email">Email:</label>
                    <input type="email" placeholder="Insira o email do aluno" name="email" id="email" value="<?php if(isset($_email)){echo $_email;}?>"><br>
                    <div><?php if(isset($error_email)){echo $error_email;}?></div>
                </div>
                <div class="phone container">
                    <label for="phone">Telefone:</label>
                    <input type="tel" placeholder="Insira o telefone do aluno" name="phone" id="phone" value="<?php if(isset($_phone)){echo $_phone;}?>"><br>
                    <div><?php if(isset($error_phone)){echo $error_phone;}?></div>
                </div>
                <div class="class container">
                    <label for="class">Turma:</label>
                    <input type="text" placeholder="Insira a turma do aluno" name="class" id="class" value="<?php if(isset($_class)){echo $_class;}?>"><br>
                    <div><?php if(isset($error_class)){echo $error_class;}?></div>
                </div>
                <div class="registro container">
                    <label for="id">Registro do Aluno:</label>
                    <input type="text" placeholder="Insira o registro do aluno" name="id" id="id" value="<?php if(isset($_id)){echo $_id;}?>"><br>
                    <div><?php if(isset($error_id)){echo $error_id;}?></div>
                </div>
                <div class="password container">
                    <label for="password">Senha:</label>
                    <input type="password" placeholder="Insira a senha do aluno" name="password" id="password"><br>
                    <div><?php if(isset($error_password)){echo $error_password;}?></div>
                </div>
                <input class="submit-btn"type="submit" value="Enviar">
            
            </form>
                <div><?php if(isset($application_successful)){echo $application_successful;}?></div> <!-- Div em que aparecera mensagem de sucesso ao enviar -->
                <div><?php if(isset($application_failed)){echo $application_failed;}?></div> <!-- Div em que aparecera mensagem de erro ao enviar -->
            
        </div>
    </body>
</html>