<?php
session_start();
include 'koneksi.php';

// ambil id dari URL
$id = $_GET['id'] ?? '';

if($id == ''){
    echo "ID tidak ditemukan!";
    exit;
}

// hapus data
$query = mysqli_query($conn, "DELETE FROM mahasiswa WHERE nim='$id'");

if($query){
    header("Location: dashboard.php");
} else {
    echo "Gagal hapus: " . mysqli_error($conn);
}
