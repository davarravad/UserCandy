<?php
require_once __DIR__ . '/../core/auth.php';
logout_user();
header('Location: ' . base_url());
exit;
