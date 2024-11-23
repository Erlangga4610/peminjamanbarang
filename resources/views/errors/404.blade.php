<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f0f0f0; /* Light grayish background */
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            text-align: center;
            background: #ecf0f1; /* Light gray background for the container */
            padding: 40px 60px;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            transition: transform 0.3s ease-in-out;
        }

        .container:hover {
            transform: scale(1.05); /* Slight zoom effect on hover */
        }

        .dinosaur-image {
            width: 250px; /* Larger dinosaur image */
            height: auto;
            animation: shake 1s ease-in-out infinite; /* Add shake animation */
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            50% { transform: translateX(10px); }
            75% { transform: translateX(-10px); }
            100% { transform: translateX(0); }
        }

        h1 {
            font-size: 3em;
            margin: 20px 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #2c3e50; /* Darker color for the header */
        }

        p {
            font-size: 1.5em;
            margin: 15px 0;
            color: #7f8c8d; /* Slightly darker gray for the paragraph */
        }

        .home-link {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 30px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 1.2em;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: background 0.3s, box-shadow 0.3s;
        }

        .home-link:hover {
            background: #c0392b;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://st4.depositphotos.com/4967103/22006/v/450/depositphotos_220068572-stock-illustration-dinosaur-404-error-tee-print.jpg" alt="Dinosaur" class="dinosaur-image">
        <h1>404 Not Found</h1>
        <p>Ups! Halaman yang Anda cari telah punah!!</p>
        <a href="/dashboard" class="home-link">Return to Home</a>
    </div>
</body>
</html>
