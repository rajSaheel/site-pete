<?php

include("./connect.php");

$data = json_decode(file_get_contents("php://input"), true);

if($data!=null&& isset($data["link"])){
        $link = $data["link"];

        $connection = connectDB();

        $query = "SELECT seo,performance,best_practices,security_score,total FROM rating WHERE link = '$link'";

        try{
            $result = $connection->query($query);
            if($result->num_rows> 0){
                $row = $result->fetch_assoc();
                echo json_encode(array(
                    'message' => 'Existing Rating Found',
                    'status'=> 'R10002',
                    'seo'=> $row['seo'],
                    'bestPractices'=> $row['best_practices'],
                    'security'=> $row['security_score'],
                    'performance'=> $row['performance'],
                    'total'=> $row['total']
                ));
            }else{
                    echo json_encode(array(
                        'message' => 'Rating does not exist',
                        'status'=> 'R10004'
                    ));
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
}