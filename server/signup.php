<?php

include("./connect.php");


// signup
$data = json_decode(file_get_contents('php://input'), true);

if ($data !== null && isset($data['username']) && isset($data['password'])) {

    $username = $data["username"];
    $password = $data["password"];
    $name = $data["name"];
    $profession = $data["profession"];

    $connection = connectDB();

    $query = "SELECT * FROM user WHERE username = '$username'";

    try{

        $result = $connection->query($query);

        if($result->num_rows > 0){
            $row = $result -> fetch_assoc();
            echo json_encode(array(
                'message' => 'User already Signed Up',
                'status'  => 'S10001',
                'uid' => $row['uid'],
            ));
        }
        else{
            $new_query = "INSERT INTO user (username,password,name,profession) VALUES('$username','$password','$name','$profession')";
            $result = $connection->query($new_query);
            $new_query = "SELECT uid  FROM user WHERE username = '$username'";
            $result =$connection->query($new_query);
            $row = $result->fetch_assoc();
            echo json_encode(array(
                'message' => 'User Signed Up',
                'status' => 'S10002',
                'uid' => $row['uid'],
            ));
        }


    }catch(Exception $e){
        throw new Exception($e->getMessage());
    }

    $connection->close();
}