<?php
    header('Content-Type: application/json');

    $aResult = array();

    if( !isset($_POST['functionname']) ) { $aResult['error'] = 'No function name!'; }

    if( !isset($_POST['arguments']) ) { $aResult['error'] = 'No function arguments!'; }

    if( !isset($aResult['error']) ) {

        switch($_POST['functionname']) {
            case 'incidencia':
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) ) {
                   $aResult['error'] = 'Error in arguments!';
                }
                else {
                    include 'connect.php';
                    try{
                        $sql='SELECT anomalia_id, zona, imagem, lingua, anomalia.descricao as a_descricao, localizacao, item.descricao as it_descri FROM
                        incidencia LEFT JOIN anomalia ON anomalia_id = anomalia.id LEFT JOIN item ON item_id=item.id WHERE anomalia_id=:aid';
                        $result = $db->prepare($sql);
                        $result->execute([':aid' => $_POST['arguments'][0]]);
                        foreach ($result as $row) {
                            $aResult['aid'] = $row['anomalia_id'];
                            $aResult['zona'] = $row['zona'];
                            $aResult['imagem'] = $row['imagem'];
                            $aResult['lingua'] = $row['lingua'];
                            $aResult['a_descricao'] = $row['a_descricao'];
                            $aResult['localizacao'] = $row['localizacao'];
                            $aResult['it_descri'] = $row['it_descri'];
                            break;
                        }
                    }
                    catch (PDOException $e) {
                        echo $e;
                        exit();
                    }
                    $db=null;
               }
               break;
            case 'proposta':
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1) ) {
                    $aResult['error'] = 'Error in arguments!';
                }
                else {
                    include 'connect.php';
                    try{
                        $proposta=explode(';',$_POST['arguments'][0]);
                        $sql='SELECT texto FROM proposta_de_correcao WHERE email=:email and nro=:nro;';
                        $result = $db->prepare($sql);
                        $result->execute([':email' => $proposta[0], ':nro' => $proposta[1]]);
                        foreach ($result as $row) {
                            $aResult['texto'] = $row['texto'];
                            break;
                        }
                    }
                    catch (PDOException $e) {
                        echo $e;
                        exit();
                    }
                    $db=null;
               }




                break;
            default:
               $aResult['error'] = 'Not found function '.$_POST['functionname'].'!';
               break;
        }

    }

    echo json_encode($aResult);
?>