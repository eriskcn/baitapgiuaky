<?php
require_once 'fnConnect.php';

$error = "";

// Connect to the database
$conn = connectDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['gender']) && isset($_POST['dob']) && isset($_POST['cit-id']) && isset($_POST['phone'])) {
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $cit_id = $_POST['cit-id'];
        $address = isset($_POST['address']) ? $_POST['address'] : "";
        $phone = $_POST['phone'];
        // Check if the patient already exists
        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM patients WHERE patient_citizenship_id = :cit_id");
        $stmt_check->bindParam(':cit_id', $cit_id);
        $stmt_check->execute();
        $count = $stmt_check->fetchColumn();

        if ($count > 0) {
            $error = "Bệnh nhân đã tồn tại.";
        } else {
            // Prepare and execute the query to insert new patient record
            $stmt = $conn->prepare("INSERT INTO patients (patient_name, patient_gender, patient_dob, patient_citizenship_id, patient_address, patient_phone) VALUES (:name, :gender, :dob, :cit_id, :address, :phone)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':cit_id', $cit_id);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':phone', $phone);

            if ($stmt->execute()) {
                //hehe
            } else {
                $error = "Đã xảy ra lỗi trong quá trình tạo hồ sơ";
            }
        }
    } else {
        $error = "Vui lòng điền đầy đủ thông tin";
    }
}

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
        <div style="display: flex;gap: 10px;align-items: center;margin: 10px auto;">
            <button id="new-patient-btn">Tạo hồ sơ</button>
        </div>
        <div id="modal-new-patient" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3 style="text-align: center;">Tạo hồ sơ</h3>
                <form class="patient-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="error"><?php echo $error; ?></div>
                    <label for="name">Họ tên:</label>
                    <input type="text" id="name" name="name" autofocus required>
                    <label for="gender">Giới tính:</label>
                    <select id="gender" name="gender" required>
                        <option value="Male">Nam</option>
                        <option value="Female">Nữ</option>
                    </select>
                    <label for="dob">Ngày sinh:</label>
                    <input type="date" id="dob" name="dob" required>
                    <label for="cit-id">Số căn cước:</label>
                    <input type="text" id="cit-id" name="cit-id" required>
                    <label for="address">Địa chỉ:</label>
                    <input type="text" id="address" name="address">
                    <label for="phone">Số điện thoại:</label>
                    <input type="tel" id="phone" name="phone" required>
                    <input type="submit" value="Submit">
                </form>
            </div>
        </div>
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
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($patients as $patient): ?>
                    <tr>
                        <td><?php echo $patient['patient_id']; ?></td>
                        <td><?php echo $patient['patient_name']; ?></td>
                        <td><?php echo $patient['patient_gender']; ?></td>
                        <td><?php echo $patient['patient_dob']; ?></td>
                        <td><?php echo $patient['patient_citizenship_id']; ?></td>
                        <td><?php echo $patient['patient_address']; ?></td>
                        <td><?php echo $patient['patient_phone']; ?></td>
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
        // Get the modal
        var modal = document.getElementById("modal-new-patient");

        // Get the button that opens the modal
        var btn = document.getElementById("new-patient-btn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // Function to open the modal
        function openModal() {
            modal.style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            modal.style.display = "none";
        }

        // Event listener for the "New Patient" button click
        btn.onclick = openModal;

        // Event listener for the close button click
        span.onclick = closeModal;

        // Event listener to close the modal when clicking outside the modal
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        // Event listener to close the modal when pressing the "Escape" key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal();
            }
        });
    </script>

    <script>
        document.getElementById("patient-form").addEventListener("submit", function(event) {
            var errorElement = document.querySelector(".error");
            var submitButton = document.getElementById("submit-btn");
            
            if (errorElement.textContent.trim() !== "") {
                // Prevent form submission if there is an error message
                event.preventDefault();
            } else {
                // Disable the submit button to prevent multiple submissions
                submitButton.disabled = true;
            }
        });
    </script>
</body>

</html>
