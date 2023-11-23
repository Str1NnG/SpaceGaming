<?php
#CONNECT DATABASE
    function PregRep($VALUE){return preg_replace('/[^0-9]/','',$VALUE);}
    function OpenVer($CARD){
        $CONN=Connect();
        $SQL = 'SELECT * FROM cards';
        $EXEC = $CONN->prepare($SQL);
        $EXEC->execute();

        $VALUE = $EXEC->Fetchall();
        foreach($VALUE as $V){
            if (trim($V['CARD_NUMBER'])==trim($CARD)):
                return 1;
            else: return 0;
            endif;
        }
    }
    function ADD_CARD($CC, $MES, $ANO, $CVV, $MSG, $RETURN){

        if (OpenVer($CC)!='1') {

            $CN = $CC != null ? PregRep($CC) : '[ERR: CARD-NUMBER]';
            $CE = $MES != null ? PregRep($MES) : '[ERR: CARD-MONTHY]';
            $CA = $ANO != null ? PregRep($ANO) : '[ERR: CARD-YEAR]';
            $CS = $CVV != null ? PregRep($CVV) : '[ERR: CARD-CVV]';
            $CM = $MSG != null ? trim(strip_tags($MSG)) : '[ERR: CARD-MESSAGE]';

            try {
                if (strpos($CN, 'ERR') or strpos($CE, 'ERR') or strpos($CA, 'ERR') or strpos($CS, 'ERR') or strpos($CM, 'ERR')) {
                    throw new Exception('(ERRO - NOS VALORES PASSADOS)');
                } else {
                    $CONN = Connect();
                    $SQL = 'INSERT INTO cards (CARD_ID,CARD_NUMBER,CARD_MES,CARD_ANO,CARD_CVV,CARD_MSG) VALUES (:CI,:CN,:CE,:CA,:CS,:CM)';
                    $FN = $CONN->prepare($SQL);
                    $FN->BindValue(':CI', '');
                    $FN->BindParam(':CN', $CN);
                    $FN->BindParam(':CE', $CE);
                    $FN->BindParam(':CA', $CA);
                    $FN->BindParam(':CS', $CS);
                    $FN->BindParam(':CM', $CM);
                    try {
                        if ($FN->execute()) {
                            if ($RETURN == 1 or $RETURN == '2') {
                                print 'DADOS SALVO COM SUCESSO';
                            }
                        } else {
                            throw new Exception('ERRO - AO SALVAR O BANCO DE DADOS');
                        }
                    } catch (Exception $exception) {
                        print'<br>';
                        print '*(Arquivo) ' . $exception->getFile() . ' | ERR: ' . $exception->getMessage();
                    }
                }
            } catch (Exception $exception) {
                print'<br>';
                print '*(Arquivo) ' . $exception->getFile() . ' | ERR: ' . $exception->getMessage();
            }
        }else{if ($RETURN=='1'): print'CARTAO JA FOI ADICIONADO'; endif;}
    }


    function HEAD_CARD($ID,$TYPE){
        try{
            if (isset($ID) and $ID!=null):

                $CONN = Connect();
                    if ($TYPE==0){$SQL = 'SELECT * FROM cards';}
                    if ($TYPE==1){$SQL = 'SELECT * FROM usuarios';}
                $STMT = $CONN->prepare($SQL);
                $STMT->execute();
                $RESULT = $STMT->Fetchall();
                if ($RESULT==null): return 'null';
                else:
                    foreach ($RESULT as $VALUE){
                        if (isset($TYPE) and $TYPE==0){if (trim($VALUE['CARD_ID'])==trim($ID)){return 1;}}
                        if (isset($TYPE) and $TYPE==1){
                            if ($VALUE['USER']==$ID): return $VALUE['LVL'];  endif;
                        }
                    }
                endif;

                else: throw new Exception('ENVIE UMA ID VALIDA');
            endif;
        }catch (Exception $exception){print '<br>' . $exception->getMessage();}
    }
    function DEL_CARD($ID,$RETURN){
        try {
            if (HEAD_CARD($ID,0) == 1):
                $CONN = Connect();
                $SQL = 'DELETE FROM cards WHERE CARD_ID = :CI';
                $DELETE = $CONN->prepare($SQL);
                $DELETE->BindParam(':CI',$ID);
                try{
                    if ($DELETE->execute()):
                        if ($RETURN==1): print'CARTÃO REMOVIDO COM SUCESSO'; endif;
                    else: throw new Exception('ERRO: PROBLEMAS EM REMOVER O CARTÃO SELECIONADO');
                    endif;
                }catch (Exception $e){print '<br>' . $e->getMessage();}

                else:throw new Exception('A ID INFORMADA '.$ID.' NAO EXISTE NA DATABASE');
            endif;

        }catch (Exception $exception){print '<br>' . $exception->getMessage();}
    }