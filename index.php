<?php
require('db/connection.php');
// clients = tabela db
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>CRUD</title>
    <style>
        .occult{
            display:none;
        }
    </style>
</head>

<body>
    <h1>C R U D</h1>
    <form id="save_form" method="post">
        <input type="text" name="name" placeholder="Digite seu nome" required>
        <input type="email" name="email" placeholder="Digite seu email" required>
        <button type="submit" name="save">Salvar</button>
    </form>

    <form class="occult" id="update_form" method="post">
        <input type="hidden" id="edit_id" name="edit_id" placeholder="ID" required>
        <input type="text" id="edit_name" name="edit_name" placeholder="Alterar nome" required>
        <input type="email" id="edit_email" name="edit_email" placeholder="Alterar email" required>
        <button type="submit" name="update">Atualizar</button>
        <button type="button" id="cancel" name="cancel">Cancelar</button>
    </form>

    <form class="occult" id="delete_form" method="post">
        <input type="hidden" id="delete_id" name="delete_id" placeholder="ID" required>
        <input type="hidden" id="delete_name" name="delete_name" placeholder="Alterar nome" required>
        <input type="hidden" id="delete_email" name="delete_email" placeholder="Alterar email" required>
        <b>Tem certeza que deseja deletar cliente <span id="client"></span>?</b>
        <button type="submit" name="delete">Confirmar</button>
        <button type="button" id="delete_cancel" name="delete-cancel">Cancelar</button>
    </form>

    <br>

    <?php  
        // Inserir dados no banco (SIMPLES)
        // $sql = $pdo->prepare("INSERT INTO clients VALUES (null,'Gabriel','test@test.com','13-08-2024')");
        // $sql->execute();

        // anti sql injection (CORRETO)
        if(isset($_POST['save']) && isset($_POST['name']) && isset($_POST['email'])) {
            $name = clearPost($_POST['name']);
            $email = clearPost($_POST['email']);
            $date = date('d-m-Y');

            // Validação campo vazio
            if ($name=="" || $name==null) {
                echo "<b style='color:red'>Nome não pode ser vazio.</b>";
                exit();
            }
            if ($email=="" || $email==null) {
                echo "<b style='color:red'>Email não pode ser vazio.</b>";
                exit();
            }

            // Validação Nome e Email
            // Verificar se nome está correto
            if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                echo "<b style='color:red'>Somente permitido letras e espaços em branco para o nome.</b>";
                exit();
            }

            // Verificar se email é válido
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<b style='color:red'>Formato de email inválido.</b>";
                exit();
            }

            $sql = $pdo->prepare("INSERT INTO clients VALUES (null,?,?,?)");
            $sql->execute(array($name,$email,$date));

            echo "<b style='color:green'>Cliente inserido com sucesso!</b>";
        }
    ?>

    <?php
        // Processo de atualização
        if(isset($_POST['update']) && isset($_POST['edit_id']) && isset($_POST['edit_name']) 
        && isset($_POST['edit_email'])) {
            $id = clearPost($_POST['edit_id']);
            $name = clearPost($_POST['edit_name']);
            $email = clearPost($_POST['edit_email']);

            // Validação campo vazio
            if ($name=="" || $name==null) {
                echo "<b style='color:red'>Nome não pode ser vazio.</b>";
                exit();
            }
            if ($email=="" || $email==null) {
                echo "<b style='color:red'>Email não pode ser vazio.</b>";
                exit();
            }

            // Validação Nome e Email
            // Verificar se nome está correto
            if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                echo "<b style='color:red'>Somente permitido letras e espaços em branco para o nome.</b>";
                exit();
            }

            // Verificar se email é válido
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<b style='color:red'>Formato de email inválido.</b>";
                exit();
            }

            // Comando para atulizar
            $sql = $pdo->prepare("UPDATE clients SET name=?, email=? WHERE id=?");
            $sql->execute(array($name,$email,$id));

            echo "Atualizado ".$sql->rowCount()." registros.";
        }
    ?>

    <?php
        // Deletar dados
        if(isset($_POST['delete']) && isset($_POST['delete_id']) && isset($_POST['delete_name']) 
        && isset($_POST['delete_email'])) {
            $id = clearPost($_POST['delete_id']);
            $name = clearPost($_POST['delete_name']);
            $email = clearPost($_POST['delete_email']);

            // Comando para deletar
            $sql = $pdo->prepare("DELETE FROM clients WHERE id=? AND name=? AND email=?");
            $sql->execute(array($id, $name, $email));

            echo "Deletado com sucesso!";
        }
    ?>

    <?php
        // Selecionar dados da tabela
        $sql = $pdo->prepare("SELECT * FROM clients ORDER BY id");
        $sql->execute();
        $data = $sql->fetchAll();

        // Exemplo com filtragem
        // $sql = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
        // $email = 'email@provedor.com';
        // $sql->execute(array($email));
        // $data = $sql->fetchAll();

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
    ?>

    <?php
        if(count($data) > 0) {
            echo "<br><br><table>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>";

            foreach($data as $key => $value) {
                echo "<tr>
                        <td>".$value['id']."</td>
                        <td>".$value['name']."</td>
                        <td>".$value['email']."</td>
                        <td><a href='#' class='btn-update' data-id='".$value['id']."'
                        data-name='".$value['name']."' data-email='".$value['email']."'>Atualizar</a> | 
                        <a href='#' class='btn-delete' data-id='".$value['id']."'
                        data-name='".$value['name']."' data-email='".$value['email']."'>Deletar</a></td>
                    </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Nenhum cliente cadastrado.</p>";
        }
    ?>

    <script src="./js/jquery-3.7.1.min.js"></script>
    <script>
        $(".btn-update").click(function() {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var email = $(this).attr('data-email');

            $('#save_form').addClass('occult');
            $('#delete_form').addClass('occult');
            $('#update_form').removeClass('occult');

            $("#edit_id").val(id);
            $("#edit_name").val(name);
            $("#edit_email").val(email);

            // alert('O ID é: '+id+" | Nome é: "+name+" | Email é: "+email);
        });

        $(".btn-delete").click(function() {
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var email = $(this).attr('data-email');

            $("#delete_id").val(id);
            $("#delete_name").val(name);
            $("#delete_email").val(email);
            $("#client").html(name);

            $('#save_form').addClass('occult');
            $('#update_form').addClass('occult');
            $('#delete_form').removeClass('occult');
        });

        $('#cancel').click(function() {
            $('#save_form').removeClass('occult');
            $('#update_form').addClass('occult');
            $('#delete_form').addClass('occult');
        })

        $('#delete_cancel').click(function() {
            $('#save_form').removeClass('occult');
            $('#update_form').addClass('occult');
            $('#delete_form').addClass('occult');
        })
    </script>

</body>

</html>