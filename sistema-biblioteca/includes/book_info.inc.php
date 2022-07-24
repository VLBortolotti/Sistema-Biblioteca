<?php
    #IF THE KEY livro DOESNT EXIST IN $_GET->
    #->THE USER IS REDIRECTED TO THE INDEX PAGE
    if (!isset($_GET['livro'])){
        header('Location: index.php');
    }
    #IF THE KEY livro EXISTS IN $_GET, IT IS->
    #->CHECKED IN THE DATABASE IF THE ID SENT->
    #->THROUGH $_GET['livro'] EXISTS IN THE DB
    else{
        $book_id = $_GET['livro'];

        #GETTING THE AMOUNT OF BOOKS IN THE DATABASE
        $sql = "SELECT * FROM livros WHERE id=?";
        $stmt = mysqli_stmt_init($__db_connect);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $book_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        #IF THE ID DOES NOT EXIST, THE ADMIN IS REDIRECTED->
        #->TO THE INDEX PAGE
        if (!$row){
            header('Location: index.php');
        }
        #IF THE ID EXISTS, THE BOOK INFO IS GOTTEN->
        #->TO BE SHOWN IN THE HTML
        else{
            $book_title = $row['titulo'];
            $book_author = $row['autor'];
            $book_pages = $row['paginas'];
            $book_genre = $row['categoria'];
            $book_description = $row['descricao'];
            $book_copies = $row['exemplares'];
            $book_borrowed = $row['retirados'];
            $book_img_type = $row['imagemtipo'];

            #SETTING THE IMAGE PATH OF THE BOOK
            if ($book_img_type){
                $img_path = "../imagens/" . $book_id . 
                                '.' . $book_img_type;
            }
            else{
                $img_path = "../imagens/book.jpg";
            }

            #IF NO OPTION IS CHOSEN AN ERROR IS SENT
            if (isset($_POST['student_email']) && !(isset($_POST['choice']))){
                $lending_error = '*Selecione uma opcao';
            }

            #THE CASE WHEN THE OPTION IS TO LEND A BOOK
            elseif (isset($_POST['student_email']) && $_POST['choice'] == 'borrow'){
                $student_email = clean_data($_POST['student_email']);
                if (!($student_info = get_student_info($__db_connect, 
                    $student_email))){
                    $lending_error = '*Usuario nao encontrado';
                }
                elseif (!($book_copies > $book_borrowed)){
                    $lending_error = '*Nao ha exemplares disponiveis';
                }
                elseif ($student_info['pending']){
                    $lending_error = '*O usuario possui devolucoes atrasadas';
                }
                elseif ($student_info['book1'] and $student_info['book2'] and 
                        $student_info['book3']){
                    $lending_error = '*O usuario ja possui 3 livros retirados';
                }
                elseif (in_array($book_id, array($student_info['book1'], 
                        $student_info['book2'], $student_info['book3']))){
                    $lending_error = '*O usuario ja esta com o livro';
                }
                else{
                    $student_can_borrow = TRUE;
                    $student_name = $student_info['firstname'];
                    $student_lastname = $student_info['lastname'];
                    $student_id = $student_info['id'];
                }
            }

            elseif (isset($_POST['student_email']) && $_POST['choice'] == 'return'){
                # ESCREVER O CODIGO PARA RETORNAR UM LIVRO
            }

            #THIS BLOCK IS FOR WHEN THERE IS A CONFIRMATION->
            #->TO LEND THE BOOK
            elseif ((isset($_POST['lending_confirmed']))){
                $student_email = $_POST['lending_confirmed'];
                
                $student_info = get_student_info($__db_connect, $student_email);

                if (!$student_info['book1']){
                    $sql_update_student = "UPDATE account_info SET book1=?,time1=? WHERE email=?";
                }
                elseif (!$student_info['book2']){
                    $sql_update_student = "UPDATE account_info SET book2=?,time2=? WHERE email=?";
                }
                elseif (!$student_info['book3']){
                    $sql_update_student = "UPDATE account_info SET book3=?,time3=? WHERE email=?";
                }

                $book_borrowed = $book_borrowed + 1;
                $sql_update_book = "UPDATE livros SET retirados=? WHERE id=?";
                $stmt = mysqli_stmt_init($__db_connect);
                mysqli_stmt_prepare($stmt, $sql_update_book);
                mysqli_stmt_bind_param($stmt, "ii", $book_borrowed, $book_id);
                if (mysqli_stmt_execute($stmt)){
                    $now = time();
                    $stmt = mysqli_stmt_init($__db_connect);
                    mysqli_stmt_prepare($stmt, $sql_update_student);
                    mysqli_stmt_bind_param($stmt, "sis", $book_id, $now, $student_email);
                    if(mysqli_stmt_execute($stmt)){
                        $lending_success = "Livro emprestado com sucesso";
                    }
                    else{
                        $lending_error = "*Erro ao concluir a aplicacao. Tente novamente.";
                    }
                }
                else{
                    $lending_error = "*Erro ao concluir a aplicacao. Tente novamente.";
                }
                
            }

        }
    }
