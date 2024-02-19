<?php
function connectDB()
{
    $conn = NULL;
    try
    {
        $conn = new PDO("mysql:host=localhost;dbname=hospital_database", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Thiết lập chế độ báo lỗi
        $conn->query("SET NAMES UTF8");
    }
    catch(PDOException $ex)
    {
        echo "<p>" . $ex->getMessage() . "</p>";
        die ("<h3> LỖI KẾT NỐI CSDL </h3>");
    }
    return $conn;
}
?>
