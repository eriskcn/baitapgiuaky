<?php
require_once 'fnConnect.php';

$error = "";

// Connect to the database
$conn = connectDB();
$stmt = $conn->query("SELECT * FROM patients");
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thang Long University General Hospital</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h2>Thang Long University General Hospital</h2>
    </header>

    <main>
        <div id="patient-list">
            <table class="patient-list">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Giới tính</th>
                        <th>Ngày sinh</th>
                        <th>Số căn cước</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Hành động</th> 
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($patients as $patient): ?>
                    <tr class="patient_row">
                        <td><?php echo $patient['patient_id']; ?></td>
                        <td><?php echo $patient['patient_name']; ?></td>
                        <td><?php echo $patient['patient_gender']; ?></td>
                        <td><?php echo $patient['patient_dob']; ?></td>
                        <td><?php echo $patient['patient_citizenship_id']; ?></td>
                        <td><?php echo $patient['patient_address']; ?></td>
                        <td><?php echo $patient['patient_phone']; ?></td>
                        <td><a href="patient-detail.php?id=<?php echo $patient['patient_id']; ?>">Chi tiết</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Thang Long University General Hospital</p>
        <a style="color: white;" href="./">Đăng xuất</a>
    </footer>

    <script>
        document.querySelectorAll('.patient-row').forEach(item => {
            item.addEventListener('click', event => {
                const patientId = item.getAttribute('data-id');
                window.location.href = 'patient_details.php?id=' + patientId;
            });
        });
    </script>
</body>

</html>
