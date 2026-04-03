<?php
session_start();

// 1. Hapus semua data session di server
session_unset();
session_destroy();

// 2. Hapus data di browser dan pindah ke index.php
echo "<script>
    localStorage.removeItem('role'); 
    alert('Anda telah berhasil keluar.');
    window.location.href = 'index.php'; 
</script>";
exit();
?>