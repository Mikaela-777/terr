<?php
session_start();
include 'koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Ambil data mahasiswa
$data = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE nim='$id'");
$row = mysqli_fetch_assoc($data);

// Ambil data prodi
$prodi = mysqli_query($conn, "SELECT * FROM prodi");

// PROSES UPDATE
if(isset($_POST['update'])){
  $nama = $_POST['nama'];
  $tgl_lahir = $_POST['tgl_lahir'];
  $alamat = $_POST['alamat'];
  $agama = $_POST['agama'];
  $kelamin = $_POST['kelamin'];
  $no_hp = $_POST['no_hp'];
  $email = $_POST['email'];
  $id_prodi = $_POST['id_prodi'];
  $password = $_POST['password'];

  // Jika password diisi
  if(!empty($password)){
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conn, "
      UPDATE mahasiswa SET
        nama='$nama',
        tgl_lahir='$tgl_lahir',
        alamat='$alamat',
        agama='$agama',
        kelamin='$kelamin',
        no_hp='$no_hp',
        email='$email',
        id_prodi='$id_prodi',
        password='$password_hash'
      WHERE nim='$id'
    ");
  } else {
    // Jika password kosong → tidak diubah
    mysqli_query($conn, "
      UPDATE mahasiswa SET
        nama='$nama',
        tgl_lahir='$tgl_lahir',
        alamat='$alamat',
        agama='$agama',
        kelamin='$kelamin',
        no_hp='$no_hp',
        email='$email',
        id_prodi='$id_prodi'
      WHERE nim='$id'
    ");
  }

  echo "<script>
    alert('Data berhasil diupdate!');
    window.location.href='dashboard.php';
  </script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Mahasiswa</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#eef2ff;">

<div class="container" style="margin-top:100px; max-width:600px;">
  <div class="card shadow-sm border-0 rounded-3">
    
    <div class="card-header text-white" style="background:#37517e;">
      <h5 class="mb-0">Edit Data Mahasiswa</h5>
    </div>

    <div class="card-body">

      <form method="POST">

        <div class="mb-3">
          <label>Nama</label>
          <input type="text" name="nama" class="form-control" value="<?= $row['nama']; ?>">
        </div>

        <div class="mb-3">
          <label>Tanggal Lahir</label>
          <input type="date" name="tgl_lahir" class="form-control" value="<?= $row['tgl_lahir']; ?>">
        </div>

        <div class="mb-3">
          <label>Alamat</label>
          <textarea name="alamat" class="form-control"><?= $row['alamat']; ?></textarea>
        </div>

        <div class="mb-3">
          <label>Agama</label>
          <select name="agama" class="form-control">
            <option <?= $row['agama']=='Islam'?'selected':''; ?>>Islam</option>
            <option <?= $row['agama']=='Kristen'?'selected':''; ?>>Kristen</option>
            <option <?= $row['agama']=='Hindu'?'selected':''; ?>>Hindu</option>
            <option <?= $row['agama']=='Budha'?'selected':''; ?>>Budha</option>
          </select>
        </div>

        <div class="mb-3">
          <label>Jenis Kelamin</label><br>
          <input type="radio" name="kelamin" value="L" <?= $row['kelamin']=='L'?'checked':''; ?>> Laki-laki
          <input type="radio" name="kelamin" value="P" <?= $row['kelamin']=='P'?'checked':''; ?>> Perempuan
        </div>

        <div class="mb-3">
          <label>No HP</label>
          <input type="text" name="no_hp" class="form-control" value="<?= $row['no_hp']; ?>">
        </div>

        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="<?= $row['email']; ?>">
        </div>

        <!-- PASSWORD BARU -->
        <div class="mb-3">
          <label>Password Baru (kosongkan jika tidak diganti)</label>
          <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
          <label>Prodi</label>
          <select name="id_prodi" class="form-control">
            <?php while($p = mysqli_fetch_assoc($prodi)): ?>
              <option value="<?= $p['id_prodi']; ?>" 
                <?= $p['id_prodi']==$row['id_prodi']?'selected':''; ?>>
                <?= $p['nama_prodi']; ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="d-flex justify-content-between">
          <a href="dashboard.php" class="btn btn-secondary btn-sm">Kembali</a>
          <button type="submit" name="update" class="btn btn-primary btn-sm">Update</button>
        </div>

      </form>

    </div>
  </div>
</div>

</body>
</html>
