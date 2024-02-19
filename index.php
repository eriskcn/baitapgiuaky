<?php
require_once 'fnConnect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Connect to the database
        $conn = connectDB();

        // Prepare and execute the query to fetch user information
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch user information
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password and redirect based on user role
        if ($user && $password == $user['password']) {
            if ($user['role'] == 'staff') {
                header("Location: staff.php");
                exit();
            } else {
                header("Location: nurse.php");
                exit();
            }
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không chính xác";
        }
    } else {
        $error = "Vui lòng nhập tên đăng nhập và mật khẩu";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Thang Long University General Hospital</title>
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

        .login-container {
            background-color: #fff;
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        #username,
        #password {
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
            margin: 10px auto;
        }

        .register-field {
            text-align: center;
            margin-top: 10px;
        }

        .register-field a {
            color: #4caf50;
            text-decoration: none;
        }

        .register-field a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="login-form">
            <div class="error"><?php echo $error; ?></div>
            <input type="text" id="username" name="username" placeholder="Tài khoản" autofocus required autocomplete="off">
            <input type="password" id="password" name="password" placeholder="Mật khẩu" required autocomplete="off">
            <input type="submit" value="Đăng nhập">
        </form>
        <div class="register-field">
            <p>Bạn chưa có tài khoản?</p>
            <a href="register.php">Đăng ký ngay!</a>
        </div>
    </div>
</body>

</html>
