<?php
require_once 'fnConnect.php';

// Connect to the database
$conn = connectDB();

// Get patient ID from URL parameter
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $patient_id = $_GET['id'];
    
    // Get patient information
    $stmt_patient = $conn->prepare("SELECT * FROM patients WHERE patient_id = :patient_id");
    $stmt_patient->bindParam(':patient_id', $patient_id);
    $stmt_patient->execute();
    $patient = $stmt_patient->fetch(PDO::FETCH_ASSOC);
    
    // Get patient visits
    $stmt_visits = $conn->prepare("SELECT * FROM visits WHERE patient_id = :patient_id");
    $stmt_visits->bindParam(':patient_id', $patient_id);
    $stmt_visits->execute();
    $visits = $stmt_visits->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Redirect back to nurse.php if patient ID is not provided
    header("Location: nurse.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['doctor_name'], $_POST['note'])) {
        $doctor_name = $_POST['doctor_name'];
        $note = $_POST['note'];

        // Insert visit into database
        $stmt_insert_visit = $conn->prepare("INSERT INTO visits (patient_id, doctor_name, note) VALUES (:patient_id, :doctor_name, :note)");
        $stmt_insert_visit->bindParam(':patient_id', $patient_id);
        $stmt_insert_visit->bindParam(':doctor_name', $doctor_name);
        $stmt_insert_visit->bindParam(':note', $note);

        if ($stmt_insert_visit->execute()) {
            // Redirect back to the same page to avoid form resubmission
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=$patient_id");
            exit();
        } else {
            $error_message = "Đã xảy ra lỗi khi thêm lượt khám mới.";
        }
    } else {
        $error_message = "Vui lòng điền đầy đủ thông tin.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin chi tiết bệnh nhân</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h2>Thông tin chi tiết bệnh nhân</h2>
    </header>

    <main>
        <div class="buttons">
            <button onclick="window.location.href='nurse.php'">Quay lại</button>
            <button onclick="openModal()">Tạo visit</button>
        </div>
        <div class="patient-details">
            <h3>Thông tin bệnh nhân</h3>
            <p><strong>ID:</strong> <?php echo $patient['patient_id']; ?></p>
            <p><strong>Họ tên:</strong> <?php echo $patient['patient_name']; ?></p>
            <p><strong>Giới tính:</strong> <?php echo $patient['patient_gender']; ?></p>
            <p><strong>Ngày sinh:</strong> <?php echo $patient['patient_dob']; ?></p>
            <p><strong>Số căn cước:</strong> <?php echo $patient['patient_citizenship_id']; ?></p>
            <p><strong>Địa chỉ:</strong> <?php echo $patient['patient_address']; ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo $patient['patient_phone']; ?></p>
            <p><strong>Ngày tạo hồ sơ:</strong> <?php echo $patient['create_date']?></p>
        </div>

        <div class="patient-visits">
            <h3>Các lượt khám</h3>
            <table>
                <thead>
                    <tr>
                        <th>Ngày khám</th>
                        <th>Bác sĩ</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($visits as $visit): ?>
                    <tr>
                        <td><?php echo $visit['visit_date']; ?></td>
                        <td><?php echo $visit['doctor_name']; ?></td>
                        <td><?php echo $visit['note']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <!-- Modal for creating new visit -->
    <div id="modal-new-visit" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Tạo lượt khám mới</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="doctor_name">Bác sĩ:</label>
                <input type="text" id="doctor_name" name="doctor_name" required>
                <label for="note">Ghi chú:</label>
                <textarea id="note" name="note" rows="4"></textarea>
                <input type="submit" value="Tạo">
            </form>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("modal-new-visit");

        // Function to open the modal
        function openModal() {
            modal.style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            modal.style.display = "none";
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>
