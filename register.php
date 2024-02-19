<?php
require_once 'fnConnect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        // Connect to the database
        $conn = connectDB();

        // Prepare and execute the query to check if username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch user information
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if username already exists
        if ($user) {
            $error = "Tên đăng nhập đã tồn tại";
        } else {
            // Prepare and execute the query to insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                // Registration successful, redirect to login page
                header("Location: ./");
                exit();
            } else {
                $error = "Đã xảy ra lỗi trong quá trình đăng ký";
            }
        }
    } else {
        $error = "Vui lòng điền đầy đủ thông tin";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .login-field {
            text-align: center;
            margin-top: 10px;
        }

        .login-field a {
            color: #4caf50;
            text-decoration: none;
        }

        .login-field a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Đăng ký</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="error"><?php echo $error; ?></div>
            <input type="text" id="username" name="username" placeholder="Tên đăng nhập" autofocus required>
            <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
            <select name="role" required>
                <option value="" disabled selected>Chọn vai trò</option>
                <option value="staff">Nhân viên</option>
                <option value="nurse">Y tá</option>
            </select>
            <input type="submit" value="Đăng ký">
            <div class="login-field">
                <p>Bạn đã có tài khoản?</p>
                <a href="./">Đăng nhập ngay!</a>
            </div>
        </form>
    </div>
</body>

</html>
