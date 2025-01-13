<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once './config/database.php';
include_once './models/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(!empty($_GET["id"])) {
            $user->id = $_GET["id"];
            $user->readOne();
            if($user->name!=null) {
                $user_arr = array(
                    "id" =>  $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "created_at" => $user->created_at
                );
                http_response_code(200);
                echo json_encode($user_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "User not found."));
            }
        } else {
            $stmt = $user->read();
            $num = $stmt->rowCount();
            if($num>0) {
                $users_arr = array();
                $users_arr["records"] = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $user_item = array(
                        "id" => $id,
                        "name" => $name,
                        "email" => $email,
                        "created_at" => $created_at
                    );
                    array_push($users_arr["records"], $user_item);
                }
                http_response_code(200);
                echo json_encode($users_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No users found."));
            }
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->name) && !empty($data->email)) {
            $user->name = $data->name;
            $user->email = $data->email;
            if($user->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "User was created."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create user."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->id) && !empty($data->name) && !empty($data->email)) {
            $user->id = $data->id;
            $user->name = $data->name;
            $user->email = $data->email;
            if($user->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "User was updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update user."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to update user. Data is incomplete."));
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->id)) {
            $user->id = $data->id;
            if($user->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "User was deleted."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to delete user."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to delete user. Please provide an ID."));
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed."));
        break;
}