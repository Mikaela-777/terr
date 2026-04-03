<?php
session_start();
include 'koneksi.php';

// 1. Proteksi halaman admin
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: index.php");
    exit();
}

// 2. Ambil email admin dari session
$email_session = $_SESSION['email'] ?? null;
if(!$email_session){
    echo "<script>alert('Session email tidak ditemukan'); window.location='index.php';</script>";
    exit();
}

// 3. Ambil data admin (Gunakan Prepared Statements untuk keamanan)
$stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE email=? AND role='admin'");
$stmt->bind_param("s", $email_session);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if(!$admin){
    echo "<script>alert('Data admin tidak ditemukan'); window.location='index.php';</script>";
    exit();
}

// 4. Proses update akun
if(isset($_POST['update_akun'])){
    $email_baru = $_POST['email'] ?? '';
    $password_lama = $_POST['old_password'] ?? '';
    $password_baru = $_POST['new_password'] ?? '';

    // Verifikasi password lama
    // Catatan: Ini hanya bekerja jika password di DB sudah di-hash dengan password_hash()
    if(password_verify($password_lama, $admin['password'])){
        $updates = [];
        $params = [];
        $types = "";

        // Update email jika diubah
        if(!empty($email_baru) && $email_baru != $admin['email']){
            $updates[] = "email=?";
            $params[] = $email_baru;
            $types .= "s";
        }

        // Update password baru jika diisi
        if(!empty($password_baru)){
            $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
            $updates[] = "password=?";
            $params[] = $password_hash;
            $types .= "s";
        }

        if(count($updates) > 0){
            // Tambahkan NIM untuk klausa WHERE
            $sql = "UPDATE mahasiswa SET " . implode(", ", $updates) . " WHERE nim=?";
            $params[] = $admin['nim'];
            $types .= "s"; // Sesuaikan jika NIM adalah integer (gunakan "i")

            $stmt_update = $conn->prepare($sql);
            $stmt_update->bind_param($types, ...$params);
            
            if($stmt_update->execute()){
                // Update session email jika email berubah agar tidak ter-logout
                if(!empty($email_baru)){
                    $_SESSION['email'] = $email_baru;
                }
                echo "<script>alert('Berhasil update akun!'); window.location='akun.php';</script>";
            } else {
                echo "<script>alert('Gagal mengupdate database.');</script>";
            }
            exit();
        } else {
            echo "<script>alert('Tidak ada perubahan yang dilakukan.'); window.location='akun.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Password lama salah!'); window.location='akun.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Akun Admin</title>
</head>
<body style="font-family:sans-serif; background:#f4f4f4;">
    <div style="max-width:500px; margin:50px auto; background:white; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
        <h2>Pengaturan Akun Admin</h2>
        <form method="POST" action="">
            <div style="margin-bottom:15px;">
                <label>Email Admin</label>
                <input type="email" name="email" value="<?= htmlspecialchars($admin['email']); ?>" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Password Lama (Wajib)</label>
                <input type="password" name="old_password" required style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label>Password Baru (Kosongkan jika tidak ganti)</label>
                <input type="password" name="new_password" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>
            <button type="submit" name="update_akun" style="background:#37517e; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">
                Update Akun
            </button>
            <a href="dashboard.php" style="text-decoration:none; color:grey; margin-left:10px;">Kembali</a>
        </form>
    </div>
</body>
</html>