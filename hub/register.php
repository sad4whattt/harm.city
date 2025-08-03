<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/database.php';

$db     = new Database();
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid session token. Please refresh and try again.';
    } else {
        $email           = trim($_POST['email'] ?? '');
        $password        = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }

        if (!$errors) {
            $conn = $db->connect();
            if ($conn) {
                $stmt = $db->query('SELECT id FROM users WHERE email = ?', [$email]);
                if ($stmt && $stmt->rowCount() > 0) {
                    $errors[] = 'This email is already registered.';
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $inserted = $db->query('INSERT INTO users (email, password_hash, created_at) VALUES (?,?,NOW())', [$email, $hash]);
                    if ($inserted) {
                        $success = 'Account created successfully. You may now <a href="login.php" class="text-primary underline">login</a>.';
                    } else {
                        $errors[] = 'Registration failed. Please try again later.';
                    }
                }
            } else {
                $errors[] = 'Database connection error.';
            }
        }
    }
}
?>

<section class="flex items-center justify-center min-h-screen pt-20 pb-24 bg-backdrop">
    <div class="w-full max-w-md bg-white/5 p-8 rounded-xl backdrop-blur-lg border border-white/10">
        <h2 class="text-2xl font-bold mb-6 text-center">Create a HarmWatch Account</h2>

        <?php if ($errors): ?>
            <div class="mb-4 text-sm text-red-400 bg-red-400/10 p-3 rounded">
                <ul class="list-disc pl-5 space-y-1">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($success): ?>
            <div class="mb-4 text-sm text-green-400 bg-green-400/10 p-3 rounded text-center">
                <?= $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <div>
                <label class="block text-sm mb-1" for="email">Email</label>
                <input class="w-full px-4 py-2 rounded-md bg-black/40 border border-white/20 focus:outline-none focus:border-primary" type="email" name="email" id="email" required autocomplete="email" />
            </div>
            <div>
                <label class="block text-sm mb-1" for="password">Password</label>
                <input class="w-full px-4 py-2 rounded-md bg-black/40 border border-white/20 focus:outline-none focus:border-primary" type="password" name="password" id="password" required autocomplete="new-password" />
            </div>
            <div>
                <label class="block text-sm mb-1" for="confirm_password">Confirm Password</label>
                <input class="w-full px-4 py-2 rounded-md bg-black/40 border border-white/20 focus:outline-none focus:border-primary" type="password" name="confirm_password" id="confirm_password" required autocomplete="new-password" />
            </div>
            <button type="submit" class="w-full py-2 bg-primary hover:bg-primary/80 rounded-md font-medium">Register</button>
        </form>
        <p class="text-xs text-gray-400 mt-4 text-center">Already have an account? <a href="login.php" class="text-primary hover:underline">Login</a></p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>