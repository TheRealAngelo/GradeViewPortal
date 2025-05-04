<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVA Grade View System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 800px;
            background-color: rgba(0, 0, 0, 0.7); /* Translucent black background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            color:rgb(255, 255, 255); /* Light green for the heading */
            margin-bottom: 20px;
        }
        /* General styles for <p> */
        p {
            color: #ddd;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* Button-specific styles */
        a.button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: linear-gradient(to right, #32cd32, #228b22); /* Light green to green gradient */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        a.button:hover {
            background: linear-gradient(to right, #228b22, #006400); /* Darker green on hover */
        }

        /* Style for the "By: ADHD" link to look like normal text */
        p a {
            text-decoration: none;
            color: inherit;
            cursor: default;
        }
    </style>
    <script>
        // JavaScript to change the background image dynamically
        document.addEventListener("DOMContentLoaded", function () {
            const images = [
                "sva-blur-bg.png"
            ]; // Add your image filenames here
            const randomImage = images[Math.floor(Math.random() * images.length)];
            document.body.style.backgroundImage = `url('../assets/images/${randomImage}')`; // Adjust the path to your images folder
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>San Vicente Academy Grade View System</h1>
        <p style="margin-bottom: 20px;">
            <a href="http://localhost/SoftwareEngineering2Final/src/admin/dashboard.php" style="text-decoration: none; color: inherit; cursor: default;">By: ADHD</a>
        </p>
        <p>
            The Grade View System for San Vicente Academy is a web-based platform designed to streamline academic communication between the faculty and students. This system provides a centralized and secure environment where faculty members can efficiently create and update student grades as well as post important announcements.
        </p>
        <p>
            In turn, students are given easy and timely access to view their academic performance and stay informed through announcements. By leveraging this system, San Vicente Academy aims to enhance transparency, accuracy, and accessibility in managing academic records and school communications.
        </p>
        <a class="button" href="login/login.php">Proceed to Login</a>
    </div>
</body>
</html>