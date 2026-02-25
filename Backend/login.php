<?php
session_start();
require_once("index.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        die("All fields are required.");
    }

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];

            header("Location: ../dashboard.php");
            exit();

        } else {
            echo "Invalid password.";
        }

    } else {
        echo "User not found.";
    }

    $stmt->close();
    $conn->close();
}