<?php

include("./connect.php");
$data = json_decode(file_get_contents('php://input'), true);

// sign in
if ($data !== null && isset($data['username']) && isset($data['password'])) {
    $username = $data['username'];
    $password = $data['password'];
    $connection = connectDB();

    $query = "SELECT * FROM user WHERE username = '$username'";

    try{

        $result = $connection->query($query);

        if($result->num_rows > 0){
            $new_query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
            $result = $connection->query($new_query);
            $row = $result->fetch_assoc();
            if($result->num_rows==1){
                echo json_encode(array(
                    'message' => 'User Signed In',
                    'status'  => 'S10003',
                    'uid' => $row['uid'],
                ));
            }
            else
            echo json_encode(array(
                'message' => 'Password does not match',
                'status'  => 'S10005',
            ));
        }
        else{
            echo json_encode(array(
                'message' => 'User not Signed Up',
                'status'  => 'S10006',
            ));
        }


    }catch(Exception $e){
        throw new Exception($e->getMessage());
    }

    $connection->close();
}
