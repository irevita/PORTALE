<?php include 'connessione.php' ?>
<?php session_start(); ?>
<?php
    $msg = ["status" => 404, "msg" => "Tutto okay!"];
                                $query = "INSERT INTO Segui(CodiceBlog, ID_Utente, Data)
                                       VALUES ({$_GET['id']},'{$_SESSION['id']}', SYSDATE())";
                                       
                                       $segui_q = mysqli_query($connessione, $query);
                                        if(!$segui_q){
                                            $query = "DELETE FROM Segui WHERE CodiceBlog = {$_GET['id']} AND ID_Utente = '{$_SESSION['id']}'";
                                                   
                                            $segui_q = mysqli_query($connessione, $query);
                                            $msg["status"] = 201;
                                            echo json_encode($msg);
                                            die;
                                        }
                            
                                
                                //$avviso = "Dati registrati con successo";
                                $msg["status"] = 200;
                                echo json_encode($msg);
                            ?>