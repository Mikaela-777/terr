<?php
include 'koneksi.php';

if(isset($_POST['register'])){
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // 🔒 HASH PASSWORD
  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  // 🔥 GENERATE NIM OTOMATIS
  $result = mysqli_query($conn, "SELECT nim FROM mahasiswa ORDER BY nim DESC LIMIT 1");
  $data_nim = mysqli_fetch_assoc($result);

  if($data_nim){
    $nim_baru = $data_nim['nim'] + 1;
  } else {
    $nim_baru = 2026001; // NIM awal
  }

  // 🔍 VALIDASI EMAIL
  $cek = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE email='$email'");

  if(mysqli_num_rows($cek) > 0){
    echo "<script>alert('Email sudah terdaftar!');</script>";
  } else {

    // 💾 INSERT DATA
    mysqli_query($conn, "
      INSERT INTO mahasiswa (nim, nama, email, password, role)
      VALUES ('$nim_baru', '$nama', '$email', '$password_hash', 'user')
    ");

    echo "<script>alert('Registrasi berhasil!'); window.location='dashboard.php';</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#eef2ff;">

<div class="container" style="margin-top:100px; max-width:500px;">
  <div class="card shadow-sm border-0 rounded-3">

    <div class="card-header text-white text-center" style="background:#37517e;">
      <h5 class="mb-0">Register Mahasiswa</h5>
    </div>

    <div class="card-body">

      <form method="POST">

        <div class="mb-3">
          <label>Nama</label>
          <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" name="register" class="btn btn-primary w-100">
          Daftar
        </button>

        <div class="text-center mt-3">
          <a href="index.php">Sudah punya akun? Login</a>
        </div>

      </form>

    </div>
  </div>
</div>

</body>
</html>