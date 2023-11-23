<?php
//    include "CONNECT.php";
    function VerUser($TYPE,$ID,$RETURN){
        try{
            if (isset($TYPE) and isset($ID) and isset($RETURN)){
                if ($TYPE!=null or $ID!=null){
                    $CONNECT=Connect();
                    $SQL='SELECT * FROM usuarios';
                        $EXEC=$CONNECT->prepare($SQL);
                        $EXEC->execute();
                        $ARRAY = $EXEC->Fetchall();

                        foreach ($ARRAY as $A){
                            if ($TYPE=='0'){
                                if (trim($A['USER'])==trim($ID)){
                                    if ($RETURN=='1'){
                                        print 'USUARIO EXISTE';
                                    }
                                    return true;
                                }
                            }
                            if ($TYPE=='1'){
                                if (trim($A['USER_ID'])==trim($ID)){
                                    if ($RETURN=='1'){
                                        print 'ID EXISTE';
                                    }
                                    return true;
                                }
                            }
                        }

                }else{throw new Exception('ENVIE OS DADOS CORRETAMENTE');}
            }
        }catch (HEAD_CARD $exception){print $exception->getMessage();}
    }

    function add_user($USER,$PASS,$LVL,$RETURN){
        try{
            if (isset($USER) and isset($PASS) and isset($LVL) and isset($RETURN)){
                if (!empty($USER) and !empty($PASS) and !empty($LVL)){

                    $LVL=trim(preg_replace('/[^0-9]/','',$LVL));

                    $CONNECT=Connect();
                    $SQL='INSERT INTO usuarios (USER_ID,USER,PASS,LVL) VALUES (:UI,:US,:PS,:LV)';
                    $EXEC=$CONNECT->prepare($SQL);
                        $EXEC->BindValue(':UI','');
                        $EXEC->BindParam(':US',$USER);
                        $EXEC->BindParam(':PS',$PASS);
                        $EXEC->BindParam(':LV',$LVL);
                    if ($EXEC->execute()){
                        if ($RETURN=='1'){
                            echo 'ADICIONADO COM SUCESSO';
                        }else{
                            throw new Exception('ERROR AO SALVAR O USUARIO');
                        }
                    }

                }else{throw new Exception('NAO DEIXE NENHUM CAMPO VAZIO!');}
            }else{throw new Exception('DEFINA TODOS OS CAMPOS ANTES DE CHAMAR A FUNÇAO');}
        }catch (Exception $exception){print $exception->getMessage();}
    }
    function del_user($ID,$RETURN){
        try {
            if (VerUser('1',$ID,'0') == true):
                $CONN = Connect();
                $SQL = 'DELETE FROM usuarios WHERE USER_ID = :CI';
                $DELETE = $CONN->prepare($SQL);
                $DELETE->BindParam(':CI',$ID);
                try{
                    if ($DELETE->execute()):
                        if ($RETURN==1): print'REMOVIDO COM SUCESSO'; endif;
                    else: throw new Exception('ERRO: PROBLEMAS EM REMOVER O CARTÃO SELECIONADO');
                    endif;
                }catch (Exception $e){print '<br>' . $e->getMessage();}

            else:throw new Exception('A ID INFORMADA '.$ID.' NAO EXISTE NA DATABASE');
            endif;

        }catch (Exception $exception){print '<br>' . $exception->getMessage();}
    }

