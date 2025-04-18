<?php
$conn = new mysqli("localhost", "root", "", "ideastream1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user inputs
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
    
    if ($check_email === false) {
        die('Error in preparing the query: ' . $conn->error);
    }

    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        // Email already exists, show an error message
        echo "Email already registered!";
    } else {
        // Insert new user into the database
$insert_query = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

if ($insert_query === false) {
    die('Error in preparing the insert query: ' . $conn->error);
}

$insert_query->bind_param("sss", $username, $email, $hashed_password);

if ($insert_query->execute()) {
    // Redirect to sign-in page after successful signup
    header("Location: signin.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}


    }
}
?>
