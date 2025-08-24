<?php
// includes/auth.php
require_once __DIR__ . '/functions.php';

function isLoggedIn()
{
    return isset($_SESSION['usuario']);
}

function isAdmin()
{
    return isLoggedIn() && ($_SESSION['usuario']['privilegioUsuario'] ?? '') === 'administrador';
}

