<?php
// 1. Set the Content-Type header to JSON
header("Content-Type: application/json");


// 2. Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myapitesting";


// 3. Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);


// 4. Check connection
if (!$conn) {
    echo json_encode(["error" => "Connection failed: " . mysqli_connect_error()]);
    exit();
}


// 5. SQL query to fetch all products
$sql = "SELECT id, name, category, price, stock_quantity FROM products";
$result = mysqli_query($conn, $sql);


// 6. Check if records exist and fetch them
$products = [];


if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    // 7. Output the data in JSON format
    echo json_encode([
        "status" => "success",
        "count" => count($products),
        "data" => $products
    ], JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No products found"
    ]);
}


// 8. Close connection
mysqli_close($conn);
?>



