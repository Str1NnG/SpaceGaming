<?php
    function Connect(){
        $HOST='localhost';
        $DB='artemis';
        $USER='root';
        $PASS='';

        try{
            $PDO = new PDO('mysql:charset=utf8;host='.$HOST.';dbname='.$DB,$USER,$PASS);
            return $PDO;
        }catch (PDOException $exception){
            print $exception->getFile().'| ERROR: '.$exception->getMessage();
        }

    }