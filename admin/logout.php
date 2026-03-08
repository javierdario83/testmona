<?php
require_once __DIR__ . '/../includes/auth.php';
logout_admin();
header('Location: ' . app_url('public/admin_login.php'));
