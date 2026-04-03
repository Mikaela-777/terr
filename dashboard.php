<?php
session_start();

// Proteksi admin
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: index.php");
    exit();
}

// Gunakan file koneksi yang sudah diperbaiki
include 'koneksi.php'; 

// Ambil data mahasiswa
$data = mysqli_query($conn, "
  SELECT 
    m.*, 
    p.nama_prodi, 
    j.nama_jurusan
  FROM mahasiswa m
  LEFT JOIN prodi p ON m.id_prodi = p.id_prodi
  LEFT JOIN jurusan j ON p.id_jurusan = j.id_jurusan
  WHERE m.role != 'admin'
");

if(!$data){
  die("Query Error: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dashboard Admin - Arsha</title>

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top" style="background: #37517e;">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.php" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">Micamp</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php#hero">Home</a></li>
          <li><a href="index.php#about">About</a></li>
          <li><a href="index.php#services">Services</a></li>
          <li><a href="index.php#contact">Contact</a></li>
          <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
  <li>
    <a href="akun.php?menu=akun">Ganti Password</a>
  </li>
<?php endif; ?>
          <li><a href="dashboard.php" class="active">Dashboard</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>

  <main class="main" style="margin-top: 100px;">
    <div style="margin-top: 100px;"></div>

    <section class="section" style="background: #eef2ff;">
      <div class="container section-title">
        <h2>Data Mahasiswa</h2>
        <p>Halaman manajemen data mahasiswa untuk Admin</p>
      </div>

      <div class="card shadow-sm border-0 rounded-3">
  <div class="card-body">

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Daftar Mahasiswa</h5>
      <a href="register.php" class="btn btn-primary btn-sm">+ Tambah</a>
    </div>

    <div class="table-responsive">
      <table class="table align-middle text-nowrap" style="border-collapse: separate; border-spacing: 0 8px;">
        
        <thead>
          <tr style="background: #37517e; color: white;">
            <th style="padding:12px;">No</th>
            <th style="padding:12px;">NIM</th>
            <th style="padding:12px;">Nama</th>
            <th style="padding:12px;">Gender</th>
            <th style="padding:12px;">Tgl Lahir</th>
            <th style="padding:12px;">No HP</th>
            <th style="padding:12px;">Prodi</th>
            <th style="padding:12px;">Jurusan</th>
            <th style="padding:12px; text-align:center;">Aksi</th>
          </tr>
        </thead>

        <tbody>
          <?php $no = 1; ?>
          <?php while($row = mysqli_fetch_assoc($data)): ?>
          <tr style="background:white; box-shadow:0 2px 6px rgba(0,0,0,0.05);">
            
            <td style="padding:12px;"><?= $no++; ?></td>
            <td style="padding:12px; font-weight:600;"><?= $row['nim']; ?></td>
            <td style="padding:12px;"><?= $row['nama']; ?></td>
            <td style="padding:12px;"><?= $row['kelamin']; ?></td>
            <td style="padding:12px;"><?= $row['tgl_lahir']; ?></td>
            <td style="padding:12px;"><?= $row['no_hp']; ?></td>
            <td style="padding:12px;"><?= $row['nama_prodi']; ?></td>
            <td style="padding:12px;"><?= $row['nama_jurusan']; ?></td>

<td style="text-align:center;">
  <?php $id = $row['nim'] ?? ''; ?>
  
  <a href="edit.php?id=<?= $id; ?>" 
     style="display:inline-block; padding:5px 10px; border:1px solid #f0ad4e; color:#f0ad4e; text-decoration:none; border-radius:5px; font-size:12px;">
     ✏️ Edit
  </a>
  <a href="hapus.php?id=<?= $row['nim']; ?>" 
     onclick="return confirm('Yakin ingin menghapus data ini?')" 
     style="padding:5px 10px; border:1px solid #dc3545; color:#dc3545; text-decoration:none; border-radius:5px; font-size:12px; margin-left:5px;">
     Hapus
  </a>
</td>

          </tr>
          <?php endwhile; ?>
        </tbody>

      </table>
    </div>

  </div>
</div>
    </section>
    <footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.php" class="d-flex align-items-center">
            <span class="sitename">Arsha</span>
          </a>
          <div class="footer-contact pt-3">
            <p>A108 Adam Street</p>
            <p>New York, NY 535022</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
            <p><strong>Email:</strong> <span>info@example.com</span></p>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Services</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Web Design</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Product Management</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#">Marketing</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12">
          <h4>Follow Us</h4>
          <p>Cras fermentum odio eu feugiat lide par naso tierra videa magna derita valies</p>
          <div class="social-links d-flex">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

      </div>
    </div>
  </footer>
  </main>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

</body>
</html>