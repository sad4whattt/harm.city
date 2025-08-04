<?php
ob_start();
session_start();

$base_url = '/hub';

$is_logged_in = isset($_SESSION['user_id']);

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function is_active($page) {
    $current_page = basename($_SERVER['PHP_SELF']);
    return $current_page == $page ? 'text-primary' : 'hover:text-primary transition';
}

?><!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HarmWatch - Enterprise-grade uptime & performance monitoring">
    <title>HarmWatch | harm.city</title>
    
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' https://cdn.tailwindcss.com 'unsafe-inline'; style-src 'self' 'unsafe-inline'; connect-src 'self'; img-src 'self' data:;">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        backdrop: '#000000',
                        primary: '#3b82f6',
                        accent: '#06b6d4'
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-backdrop text-gray-200 selection:bg-primary/40 overflow-x-hidden">
    <header class="fixed top-0 inset-x-0 z-50 backdrop-blur-md bg-black/40 border-b border-white/10">
        <div class="max-w-7xl mx-auto flex items-center justify-between py-4 px-6">
            <a href="<?php echo $base_url; ?>/" class="text-xl font-semibold tracking-wide">harm.<span class="text-primary">city</span> <span class="text-xs font-normal ml-1">/ HarmWatch</span></a>
            
            <nav class="hidden md:flex gap-8 text-sm">
                <?php if ($is_logged_in): ?>
                    <a href="<?php echo $base_url; ?>/dashboard.php" class="<?php echo is_active('dashboard.php'); ?>">Dashboard</a>
                    <a href="<?php echo $base_url; ?>/monitors.php" class="<?php echo is_active('monitors.php'); ?>">Monitors</a>
                    <a href="<?php echo $base_url; ?>/alerts.php" class="<?php echo is_active('alerts.php'); ?>">Alerts</a>
                    <a href="<?php echo $base_url; ?>/settings.php" class="<?php echo is_active('settings.php'); ?>">Settings</a>
                    <a href="<?php echo $base_url; ?>/logout.php" class="text-red-400 hover:text-red-300 transition">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>/features.php" class="<?php echo is_active('features.php'); ?>">Features</a>
                    <a href="<?php echo $base_url; ?>/pricing.php" class="<?php echo is_active('pricing.php'); ?>">Pricing</a>
                    <a href="<?php echo $base_url; ?>/register.php" class="<?php echo is_active('register.php'); ?>">Register</a>
                    <a href="<?php echo $base_url; ?>/login.php" class="<?php echo is_active('login.php'); ?>">Login</a>
                <?php endif; ?>
                <a href="https://github.com/sad4whattt/harm.city" target="_blank" class="flex items-center gap-1 hover:text-primary transition">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                    Open Source
                </a>
            </nav>

            <button class="md:hidden flex items-center p-2 rounded-md hover:bg-white/5 transition" id="mobile-menu-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div class="md:hidden hidden absolute w-full bg-black/80 backdrop-blur-md border-b border-white/10" id="mobile-menu">
            <div class="px-6 py-4 space-y-3">
                <?php if ($is_logged_in): ?>
                    <a href="<?php echo $base_url; ?>/dashboard.php" class="block py-2 <?php echo is_active('dashboard.php'); ?>">Dashboard</a>
                    <a href="<?php echo $base_url; ?>/monitors.php" class="block py-2 <?php echo is_active('monitors.php'); ?>">Monitors</a>
                    <a href="<?php echo $base_url; ?>/alerts.php" class="block py-2 <?php echo is_active('alerts.php'); ?>">Alerts</a>
                    <a href="<?php echo $base_url; ?>/settings.php" class="block py-2 <?php echo is_active('settings.php'); ?>">Settings</a>
                    <a href="<?php echo $base_url; ?>/logout.php" class="block py-2 text-red-400 hover:text-red-300 transition">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>/features.php" class="block py-2 <?php echo is_active('features.php'); ?>">Features</a>
                    <a href="<?php echo $base_url; ?>/pricing.php" class="block py-2 <?php echo is_active('pricing.php'); ?>">Pricing</a>
                    <a href="<?php echo $base_url; ?>/register.php" class="block py-2 <?php echo is_active('register.php'); ?>">Register</a>
                    <a href="<?php echo $base_url; ?>/login.php" class="block py-2 <?php echo is_active('login.php'); ?>">Login</a>
                <?php endif; ?>
                <a href="https://github.com/sad4whattt/harm.city" target="_blank" class="flex items-center gap-1 py-2 hover:text-primary transition">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                    Open Source
                </a>
            </div>
        </div>
    </header>

    <main class="pt-24">