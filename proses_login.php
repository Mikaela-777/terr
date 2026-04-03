<?php
session_start();
include 'koneksi.php';

$nim = $_POST['nim'];
$password = MD5($_POST['password']);

$query = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE nim='$nim' AND password='$password'");
$data = mysqli_fetch_assoc($query);

if ($data) {
    $_SESSION['nim'] = $data['nim'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['role'] = $data['role'];

    header("Location: index.php");
} else {
    echo "<script>alert('Login gagal');window.location='login.php';</script>";
}
?>
