<?php
   
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: POST");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
    // connect to database
    function con($username,$password,$database){
        return new mysqli("localhost",base64_decode($username), base64_decode($password),base64_decode($database));
    }
// Check connection
    $postdata = file_get_contents("php://input");
    if (isset($postdata)) {
        $request = json_decode($postdata);
        $action = $request->action;
        $sql = $request->sql;
        $database = $request->database;
        $password = $request->password;
        $username = $request->username;

switch ($action){
    case "query_get":
    $conn=con($username,$password,$database);$res = $conn->query($sql);echo $res;$conn->close();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };
    break;
    case "multi_query_set":
    $conn=con($username,$password,$database);$res = mysqli_query($conn, $sql);echo $res;mysqli_close($conn);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };
    break;
    case "query_set":
    $conn=con($username,$password,$database);$res = $conn->query($sql);echo $res;$conn->close();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };
    break;
    default:
    echo "no action selected".$request;
       }
    }
    else {
        echo "Not called properly with Action parameter!";
    }

?>
