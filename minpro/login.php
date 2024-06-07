<?php 
include 'koneksi.php';

// Mengambil data dari form login
$email = $_POST['email'];
$password = $_POST['password'];

// Query untuk mengambil data pengguna dari database
$query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = $conn->query($query);

// Memeriksa apakah ada baris yang sesuai dengan kriteria login
if ($result->num_rows > 0) {
    // Jika ada, maka login berhasil
    header("Location: index.php");
} else {
    // Jika tidak, maka login gagal
    header("Location: login_form.php");
    $_SESSION['error'] = "Email atau password salah.";
}
?>
