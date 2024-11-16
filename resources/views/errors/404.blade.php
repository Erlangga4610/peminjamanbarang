<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    
    <style>
        body {
            background-color: #f8d7da; /* Latar belakang merah muda */
            font-family: 'Arial', sans-serif;
        }
        .container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .error-content {
            max-width: 600px;
            margin: 0 auto;
            color: #721c24; /* Warna teks merah gelap */
        }
        .error-icon {
            font-size: 10rem;
            color: #dc3545; /* Warna merah terang untuk ikon */
            margin-bottom: 20px;
        }
        .error-message {
            font-size: 1.25rem;
            color: #6c757d;
        }
        .btn-primary {
            padding: 15px 30px;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            background-color: #dc3545; 
            border-color: #dc3545;
        }
        .btn-primary:hover {
            background-color: #c82333; 
            border-color: #bd2130;
            transition: background-color 0.3s ease;
        }
        .btn-primary:focus {
            box-shadow: none;
        }
    </style>
</head>
@livewireStyles
<body>
    <div class="container">
        <div class="error-content">
            <!-- Ikon 404 -->
            <div class="error-icon">
                <i class="bi bi-emoji-dizzy"></i> <!-- Anda bisa mengganti ini dengan ikon lain -->
            </div>
            <!-- Teks Error -->
            <h1 class="display-1 fw-bold" style="color: #dc3545;">404</h1>
            <h2 class="error-message">Ups! Halaman yang Anda cari tidak ada.</h2>
            <p class="error-message">Mungkin telah dipindahkan atau dihapus.</p>
            <!-- Tombol untuk kembali ke homepage -->
            <button onclick="window.location.href='/dashboard'" class="btn btn-primary">
                Kembali ke Beranda</button>
        </div>
    </div>
</body>
@livewireScripts
</html>
