<?php
session_start();
include_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // user input ni
    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
      
        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // verify hashbrowns
            if (password_verify($password, $row['password'])) {
                
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $row['role'];

                // e direct niya si user based sa role niya
                if ($row['role'] == 'faculty') {
                    header("Location: faculty/maindashboard.php");
                } elseif ($row['role'] == 'student') {
                    header("Location: student/maindashboard.php");
                }
                exit();
            } else {
                $error = "*Invalid username or password.";
            }
        } else {
            $error = "*Invalid username or password.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <style>
        html{
            height: 100%;
            margin: 0;
            padding: 0; 
            background-image: url("../assets/images/sva-blur-bg.png");
            background-size: cover; 
            background-repeat: no-repeat; 
            background-position: center center;
        } 
        body{
            background-color: transparent; 
        }
    </style>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-img-container">
                <img src="../assets/images/SVA_logo.png">
            </div>
            <div class="login-inputs-things">
                <h1>Grade View Portal</h1>
                <h2>SIGN IN</h2>
                <div class="login-container-inputs">
                    <form method="POST" action="">
                        <input type="text" id="username" name="username" placeholder="Username" required>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <?php if (isset($error)): ?>
                            <div class="error"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <button type="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>