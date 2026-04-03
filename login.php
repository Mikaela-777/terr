<?php
session_start();
include 'koneksi.php';

if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Ambil data berdasarkan email saja
    $query = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE email='$email'");
    $data = mysqli_fetch_assoc($query);

    if($data){
        // Cek password (hash ATAU plain text lama)
        if(password_verify($password, $data['password']) || $password === $data['password']){
            
            // Upgrade ke hash kalau masih plain text
            if($password === $data['password']){
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE mahasiswa SET password='$newHash' WHERE email='$email'");
            }

            $_SESSION['role'] = $data['role'];
            $_SESSION['nama'] = $data['nama'];
            $_SESSION['email'] = $data['email'];

            echo "<script>
                alert('Login berhasil sebagai ".$data['role']."');
                window.location.href='dashboard.php';
            </script>";
        } else {
            echo "<script>alert('Password salah'); window.location='login.html';</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan'); window.location='login.html';</script>";
    }

} else {
    echo "Akses tidak valid";
}
?>
