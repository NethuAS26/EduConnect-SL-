<?php
session_start();

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) || isset($_SESSION['admin_id']);
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['admin_id']);
}

// Function to check if user is student
function isStudent() {
    return isset($_SESSION['user_id']);
}

// Function to get current user info
function getCurrentUser() {
    if (isAdmin()) {
        return [
            'type' => 'admin',
            'id' => $_SESSION['admin_id'],
            'email' => $_SESSION['admin_email'],
            'campus' => $_SESSION['campus']
        ];
    } elseif (isStudent()) {
        return [
            'type' => 'student',
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'] ?? 'Student',
            'email' => $_SESSION['user_email'] ?? 'No email set'
        ];
    }
    return null;
}

// Function to require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Function to require admin access
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: login.php');
        exit;
    }
}

// Function to require student access
function requireStudent() {
    if (!isStudent()) {
        header('Location: login.php');
        exit;
    }
}
?>
