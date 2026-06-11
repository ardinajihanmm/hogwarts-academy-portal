<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', '/hogwarts-academy-portal');

function requireLogin(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/pages/login.php');
        exit;
    }
}

function requireStudent(): void {
    requireLogin();

    if ($_SESSION['role'] !== 'student') {
        header('Location: ' . BASE_URL . '/pages/admin/dashboard.php');
        exit;
    }
}

function requireAdmin(): void {
    requireLogin();

    if ($_SESSION['role'] !== 'admin') {
        header('Location: ' . BASE_URL . '/pages/student/dashboard.php');
        exit;
    }
}

function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}