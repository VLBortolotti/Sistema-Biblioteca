<?php include '../includes/db_connection.inc.php'; ?> <!-- CONNECTION TO DATABASE-->
<?php include '../includes/session.inc.php'; ?> <!-- STARTS A SESSION-->
<?php include '../includes/functions.inc.php'; ?> <!-- PHP FUNCTIONS -->
<?php include '../includes/check_if_admin.inc.php'; ?> <!-- CHECKS IF WHO IS ACCESSING THIS FILE IS admin-->
<?php include '../includes/logout.inc.php'; ?> <!-- LOGOUT SCRIPT -->
<?php include '../includes/book_info.inc.php'; ?> <!-- THIS PAGE'S SCRIPT -->

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Livro</title>
        <link rel="stylesheet" href="../css/livro.css">
    </head>
    <body>
        <section class="space"></section>

        <div class="menu-mobile">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
        <?php include '../includes/admin_menu.inc.php'; ?> <!-- ADMIN'S MENU -->
        
        <div class="infolivro">
            <?php
                #BOOK INFO
                echo "<h1 class='titulo'>$book_title</h1>";
                echo "<div class='ladinho'><img src='$img_path' width='240px' height='340px'>";
                echo "<div class='informacoes_livro'> <p class='conteudo'><span class='bold'>
                        Descricao: </span>$book_description</p>";
                echo "<p class='conteudo'><span class='bold'>Autor:</span> $book_author</p>";
                echo "<p class='conteudo'><span class='bold'>Paginas:</span> $book_pages</p>";
                echo "<p class='conteudo'><span class='bold'>Categoria:</span> $book_genre</p>";
                echo "<p class='conteudo'><span class='bold'>Exemplares:</span> $book_copies</p>";
                echo "<p class='conteudo'><span class='bold'>Emprestados:</span> 
                        $book_borrowed</p></div></div>";

                #THIS ARE THE INPUTS TO LEND OR RETURN A BOOK
                #IF THE INFORMED STUDENT IS ABLE TO BORROW OR RETURN->
                #->THE BOOK, THIS BLOCK IS HIDDEN AND THE BLOCK WITH->
                #->THE CONFIRMATION IS SHOWN
                if (!isset($student_can_borrow) and !isset($student_can_return)){
                    echo "<div class='emprestarlivro'><form action=".$_SERVER['PHP_SELF'].'?livro=' . $book_id . " method='post'>";
                        echo "<h2 class='titulo-emprestarlivro'>Empréstimo</h2><label for='email'><span class='bold'>Email do usuario:</span></label>";
                        echo "<input type='email' name='student_email' id='email'>";
                        echo "<label for='borrow'><span class='bold'>Emprestar:</span></label>";
                        echo "<input type='radio' name='choice' value='borrow' id ='borrow'>";
                        echo "<label for='return'><span class='bold'>Devolver:</span></label>";
                        echo "<input type='radio' name='choice' value='return' id='return'>";
                        echo "<input type='submit' class='inputenviar' value='Enviar'>";
                    if(isset($lending_returning_error)){echo $lending_returning_error;}
                    if(isset($lending_success)){echo $lending_success;}
                    echo "</form>";
                }
            ?>
            <div>
                <?php
                    #THIS BLOCK ONLY APPEARS WHEN THE STUDENT IS ABLE TO BORROW->
                    #->THE BOOK. IT SHOWS THE STUDENTS'S INFO AND A CONFIRMATION->
                    #->BOX TO LEND THE BOOK
                    if (isset($student_can_borrow)){

                        #STUDENT INFO
                        echo "<p class='conteudo'><span class='bold'>Nome:</span> 
                                $student_name" . ' ' . "$student_lastname</p>";
                        echo "<p class='conteudo'><span class='bold'>ID:</span> 
                                $student_id</p>";
                        echo "<p class='conteudo'><span class='bold'>Email:
                                </span> $student_email</p>";
                        echo "<p class='conteudo'><span class='bold'>Livro a ser emprestado:
                                </span> $book_title</p>";
                        
                        #CONFIRM LENDING A BOOK BUTTON
                        echo "<form id='confirm-form' action=" . $_SERVER['PHP_SELF'] . "?livro=" . $book_id . " method='post'>";
                            echo "<input type='hidden' value='$student_email' name='lending_confirmed'>";
                            echo "<input class='confirm-button' type='submit' value='Emprestar'>";


                        #CANCEL LENDING A BOOK BUTTON
                        echo "<a href='" . $_SERVER['PHP_SELF'] . '?livro=' . $book_id . "'" . 
                                "><input class='confirm-button' type='button' value='Cancelar'></a>";
                                echo "</form>";
                    }
                    elseif (isset($student_can_return)){

                        #STUDENT INFO
                        echo "<p class='conteudo'><span class='bold'>Nome:</span> 
                                $student_name" . ' ' . "$student_lastname</p>";
                        echo "<p class='conteudo'><span class='bold'>ID:</span> 
                                $student_id</p>";
                        echo "<p class='conteudo'><span class='bold'>Email:
                                </span> $student_email</p>";
                        echo "<p class='conteudo'><span class='bold'>Livro a ser devolvido:
                                </span> $book_title</p>";
                        
                        #CONFIRM RETURNING A BOOK BUTTON
                        echo "<form id='confirm-form'action=" . $_SERVER['PHP_SELF'] . "?livro=" . $book_id . " method='post'>";
                            echo "<input type='hidden' value='$student_email' name='returning_confirmed'>";
                            echo "<input class='confirm-button' type='submit' value='Devolver'>";
                        

                        #CANCEL RETURNING A BOOK BUTTON
                        echo "<a href='" . $_SERVER['PHP_SELF'] . '?livro=' . $book_id . "'" . 
                                "><input class='confirm-button' type='button' value='Cancelar'></a>";
                        echo "</form>";
                    }
                ?>
                <div>
                    <?php
                        if ($book_borrowed > 0){
                            echo "<div class='retirado-info'><h1 class=titulo-retirarlivro>Informações da retirada</h1>";
                            echo "<br>";
                            while ($row = mysqli_fetch_assoc($result)){
                                $return_date_array = returnDate($__db_connect, $book_id, $row['email']);
                                echo "<div class='usuario-retirada'><span class='bold'>Nome:</span> ";
                                echo $row['firstname'] . ' ' . $row['lastname'] . " ";
                                echo "<br><span class='bold'>ID:</span> ";
                                echo $row['id'] . " ";
                                echo "<br><span class='bold'>Email:</span> ";
                                echo $row['email'] . " ";
                                echo "<br><span class='bold'>Retirada:</span> ";
                                echo $return_date_array['borrowed_date'];
                                echo "<br><span class='bold'>Devolucao:</span> ";
                                echo $return_date_array['return_date'];
                                echo "<br><span class='bold'>Atrasado:</span> ";
                                echo $return_date_array['pending'];
                                echo "<br></div>";
                            }
                        }
                    ?>
                </div>
                
            </div>
        </div>
        <script src="../js/menumobile.js"></script>
    </body>
</html>
