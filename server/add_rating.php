<?php

include("./connect.php");

$data = json_decode(file_get_contents("php://input"), true);

if($data!=null&&isset($data["uid"]) && isset($data["link"]) && isset($data["seo"]) && 
    isset($data["security"]) && isset($data["performance"])&&isset($data["bestPractices"])&& isset($data["total"])){

        $uid = $data["uid"];
        $link = $data["link"];
        $seo = $data["seo"];
        $security = $data["security"];
        $performance = $data["performance"];
        $bestPractices = $data["bestPractices"];
        $total = $data["total"];

        $connection = connectDB();

        $query = "SELECT seo,performance,best_practices,security_score,total FROM rating WHERE link = '$link'";

        try{
            $result = $connection->query($query);
            if($result->num_rows!= 0){
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
                $query = "INSERT INTO rating (uid,link,seo,security_score,performance,best_practices,total) 
                VALUES('$uid','$link','$seo','$security','$performance','$bestPractices','$total')";
                $result = $connection->query($query);
                if($result===true){
                    echo json_encode(array(
                        'message' => 'Link added to DB',
                        'status'=> 'R10001'
                    ));
                }else{
                    echo json_encode(array(
                        'message'=> 'Link could not be added',
                        'status'=> 'R10003'
                    ));
                }
                
                
                
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }