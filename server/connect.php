<?php

    function connectDB(){

        try {
        
            $url="localhost";
            $user="root";
            $db="pete-crawler";
            $password="";
            $connection = new mysqli($url,$user,$password,$db);
            return $connection;
            
        }


        catch(Exception $e) {
            throw new Exception($e);
        }
    }
