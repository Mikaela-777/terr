<?php
session_start();

if (isset($_SESSION['nim'])) {
    echo json_encode([
        "login" => true,
        "nama" => $_SESSION['nama'],
        "role" => $_SESSION['role']
    ]);
} else {
    echo json_encode([
        "login" => false
    ]);
}
?>
