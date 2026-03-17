<?php
header("Content-Type: application/json");

$conn = mysqli_connect("localhost", "root", "", "myapitesting");

if (!$conn) {
    echo json_encode(["error" => "Connection failed"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
    echo json_encode([
        "status" => "error",
        "message" => "Only DELETE method allowed"
    ]);
    exit();
}

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input["id"])) {
    echo json_encode([
        "status" => "error",
        "message" => "ID is required"
    ]);
    exit();
}

$id = (int)$input["id"];

$sql = "DELETE FROM products WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo json_encode([
        "status" => "success",
        "message" => "Product deleted successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>