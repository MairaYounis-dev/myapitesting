<?php
// 1. Set the Content-Type header to JSON
header("Content-Type: application/json");


// 2. Database credentials
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "myapitesting";


// 3. Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);


// 4. Check connection
if (!$conn) {
    echo json_encode(["error" => "Connection failed: " . mysqli_connect_error()]);
    exit();
}


// 5. Accept only POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "status"  => "error",
        "message" => "Only POST method is allowed"
    ]);
    mysqli_close($conn);
    exit();
}


// 6. Read and decode the incoming JSON body
$input = json_decode(file_get_contents("php://input"), true);


// 7. Validate required fields
$required = ["name", "category", "price", "stock_quantity"];
$missing  = [];


foreach ($required as $field) {
    if (!isset($input[$field]) || $input[$field] === "") {
        $missing[] = $field;
    }
}


if (!empty($missing)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Missing required fields: " . implode(", ", $missing)
    ]);
    mysqli_close($conn);
    exit();
}


// 8. Sanitize inputs
$name           = mysqli_real_escape_string($conn, trim($input["name"]));
$category       = mysqli_real_escape_string($conn, trim($input["category"]));
$price          = (float) $input["price"];
$stock_quantity = (int)   $input["stock_quantity"];


// 9. SQL query to insert the product
$sql = "INSERT INTO products (name, category, price, stock_quantity)
        VALUES ('$name', '$category', $price, $stock_quantity)";


$result = mysqli_query($conn, $sql);


// 10. Check if insert was successful and respond
if ($result) {
    echo json_encode([
        "status"     => "success",
        "message"    => "Product saved successfully",
        "product_id" => mysqli_insert_id($conn)
    ], JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        "status"  => "error",
        "message" => "Failed to save product: " . mysqli_error($conn)
    ]);
}


// 11. Close connection
mysqli_close($conn);
?>
