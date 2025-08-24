<?php
// includes/auth.php
require_once __DIR__ . '/functions.php';

function isLoggedIn()
{
    return isset($_SESSION['user']);
}

function isAdmin()
{
    return isLoggedIn() && ($_SESSION['user']['privilegioUsuario'] ?? '') === 'administrador';
}

