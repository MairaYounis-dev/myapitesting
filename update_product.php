<?php
header("Content-Type: application/json");

$conn = mysqli_connect("localhost", "root", "", "myapitesting");

if (!$conn) {
    echo json_encode(["error" => "Connection failed"]);
    exit();
}

// Only PUT or POST
if ($_SERVER["REQUEST_METHOD"] !== "PUT" && $_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Only PUT or POST allowed"]);
    exit();
}

$input = json_decode(file_get_contents("php://input"), true);

// Required fields
if (!isset($input["id"])) {
    echo json_encode(["status" => "error", "message" => "ID is required"]);
    exit();
}

$id = (int)$input["id"];

// Optional fields
$fields = [];

if (isset($input["name"])) {
    $name = mysqli_real_escape_string($conn, $input["name"]);
    $fields[] = "name='$name'";
}

if (isset($input["category"])) {
    $category = mysqli_real_escape_string($conn, $input["category"]);
    $fields[] = "category='$category'";
}

if (isset($input["price"])) {
    $price = (float)$input["price"];
    $fields[] = "price=$price";
}

if (isset($input["stock_quantity"])) {
    $stock = (int)$input["stock_quantity"];
    $fields[] = "stock_quantity=$stock";
}

if (empty($fields)) {
    echo json_encode(["status" => "error", "message" => "No fields to update"]);
    exit();
}

$sql = "UPDATE products SET " . implode(", ", $fields) . " WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    echo json_encode([
        "status" => "success",
        "message" => "Product updated successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>