<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard/index.php');
    exit;
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/database.php';

$db = new Database();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $error = 'Invalid session token. Please refresh and try again.';
    } else {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
            $error = 'Email or password is invalid.';
        } else {
            $conn = $db->connect();
            if ($conn) {
                $stmt = $db->query('SELECT id, password_hash FROM users WHERE email = ?', [$email]);
                $user = $stmt ? $stmt->fetch() : false;

                if ($user && password_verify($password, $user['password_hash'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    header('Location: dashboard.php');
                    exit;
                }
            }
            $error = 'Incorrect email or password.';
        }
    }
}
?>

<section class="flex items-center justify-center min-h-screen pt-20 pb-24 bg-backdrop">
    <div class="w-full max-w-md bg-white/5 p-8 rounded-xl backdrop-blur-lg border border-white/10">
        <h2 class="text-2xl font-bold mb-6 text-center">Login to HarmWatch</h2>
        <?php if ($error): ?>
            <div class="mb-4 text-sm text-red-400 bg-red-400/10 p-3 rounded"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <form method="POST" novalidate class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <div>
                <label class="block text-sm mb-1" for="email">Email</label>
                <input class="w-full px-4 py-2 rounded-md bg-black/40 border border-white/20 focus:outline-none focus:border-primary" type="email" name="email" id="email" required autocomplete="email" />
            </div>
            <div>
                <label class="block text-sm mb-1" for="password">Password</label>
                <input class="w-full px-4 py-2 rounded-md bg-black/40 border border-white/20 focus:outline-none focus:border-primary" type="password" name="password" id="password" required autocomplete="current-password" />
            </div>
            <button type="submit" class="w-full py-2 bg-primary hover:bg-primary/80 rounded-md font-medium">Login</button>
        </form>
        <p class="text-xs text-gray-400 mt-4 text-center">Don't have an account? <a href="register.php" class="text-primary hover:underline">Register</a></p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>