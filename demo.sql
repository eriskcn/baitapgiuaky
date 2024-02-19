-- Tạo database hospital_database
CREATE DATABASE hospital_database;

USE hospital_database;

-- Tạo bảng users
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    role ENUM('staff', 'nurse') NOT NULL
);

-- Tạo bảng patients
CREATE TABLE patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    patient_gender ENUM('Male', 'Female', 'Other') NOT NULL,
    patient_dob DATE NOT NULL,
    patient_citizenship_id VARCHAR(20) NOT NULL,
    patient_address TEXT NOT NULL,
    patient_phone VARCHAR(20) NOT NULL,
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng visits
CREATE TABLE visits (
    visit_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    visit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    doctor_name VARCHAR(255),
    note TEXT,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id)
);

-- Thêm một bản ghi demo vào bảng users
INSERT INTO users (username, password, role) VALUES ('demo_user', 'demo_password', 'staff');

-- Thêm một bản ghi demo vào bảng patients
INSERT INTO patients (patient_name, patient_gender, patient_dob, patient_citizenship_id, patient_address, patient_phone) 
VALUES ('John Doe', 'Male', '1990-01-01', '123456789', '123 Main Street, City, Country', '1234567890');

-- Thêm một bản ghi demo vào bảng visits
INSERT INTO visits (patient_id, doctor_name, note)
VALUES (1, 'Dr. Smith', 'Regular check-up');

