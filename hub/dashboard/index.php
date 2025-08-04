<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/database.php';

$db  = new Database();
$conn = $db->connect();

$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['interval_id'],$_POST['interval_seconds'])){
    if(!hash_equals($_SESSION['csrf_token']??'',$_POST['csrf_token']??'')){
        $errors[]='Invalid session token.';
    }else{
        $iid=(int)$_POST['interval_id'];
        $ivals=[60,300,600,1800];
        $val=(int)$_POST['interval_seconds'];
        if(!in_array($val,$ivals)){$errors[]='Invalid interval selected.';}
        if(!$errors){
            $db->query('UPDATE endpoints SET interval_seconds=? WHERE id=? AND user_id=?',[$val,$iid,$_SESSION['user_id']]);
            header('Location: index.php');exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid session token.';
    } else {
        $deleteId = (int)$_POST['delete_id'];
        $db->query('DELETE FROM endpoints WHERE id = ? AND user_id = ?', [$deleteId, $_SESSION['user_id']]);
        header('Location: index.php');
        exit;
    }
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['endpoint_url'])) {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid session token.';
    } else {
        $url = trim($_POST['endpoint_url']);
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            $errors[] = 'Please enter a valid URL.';
        }

        if (!$errors) {
                        $interval = 300; // default 5-min
            $db->query('INSERT INTO endpoints (user_id, url, interval_seconds) VALUES (?,?,?)', [$_SESSION['user_id'], $url, $interval]);
            header('Location: index.php');
            exit;
        }
    }
}

$stmt = $db->query('SELECT * FROM endpoints WHERE user_id = ? ORDER BY created_at DESC', [$_SESSION['user_id']]);
$endpoints = $stmt ? $stmt->fetchAll() : [];



?>

<section class="pt-24 pb-32 bg-backdrop min-h-screen">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-12">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold">Dashboard</h1>
                <p class="text-gray-400 mt-2 text-sm">Monitor and manage your endpoints in one place.</p>
            </div>
            <form method="POST" class="mt-6 sm:mt-0 flex gap-3 w-full sm:w-auto">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="url" name="endpoint_url" placeholder="https://example.com" required class="flex-1 sm:w-72 px-4 py-2 rounded-md bg-black/40 border border-white/20 focus:outline-none focus:border-primary" />
                <button class="px-6 py-2 bg-primary hover:bg-primary/80 rounded-md font-medium">Add</button>
            </form>
        </div>

        <?php if ($errors): ?>
            <div class="mb-6 bg-red-400/10 text-red-400 text-sm p-4 rounded">
                <ul class="list-disc pl-5 space-y-1">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!$endpoints): ?>
            <div class="text-center text-gray-500 mt-20" data-animate>
                <p>You haven\'t added any endpoints yet.</p>
                <p class="text-sm mt-2">Add your first URL above to start monitoring.</p>
            </div>
        <?php else: ?>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8" data-animate>
                <?php foreach ($endpoints as $ep): ?>
                    <div class="bg-white/5 p-6 rounded-xl backdrop-blur-lg border border-white/10 hover:border-primary transition group">
                        <div class="flex justify-between items-center mb-4">
                            <form method="POST" class="mr-2">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="interval_id" value="<?= (int)$ep['id']; ?>">
                                <select name="interval_seconds" class="text-xs bg-black/40 border border-white/10 rounded px-1 py-0.5" onchange="this.form.submit()">
                                    <?php $opts=[60=>'1m',300=>'5m',600=>'10m',1800=>'30m']; foreach($opts as $sec=>$label): ?>
                                        <option value="<?= $sec; ?>" <?= $ep['interval_seconds']==$sec?'selected':''; ?>><?= $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                            <a href="<?= htmlspecialchars($ep['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="font-medium break-all text-primary group-hover:underline">
                                <?= htmlspecialchars($ep['url'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                            <span class="text-xs <?= $ep['last_status'] === 'up' ? 'text-green-400' : ($ep['last_status'] === 'down' ? 'text-red-400' : 'text-gray-400'); ?>">
    <?= $ep['last_status'] ? strtoupper($ep['last_status']) : '—'; ?>
</span>
<form method="POST" class="ml-2">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="delete_id" value="<?= (int)$ep['id']; ?>">
    <button type="submit" class="text-red-400 hover:text-red-300 text-xs" title="Remove" onclick="return confirm('Remove this endpoint?');">&times;</button>
</form>
                        </div>
                        <p class="text-xs text-gray-400">Last Checked: <?= $ep['last_checked'] ? htmlspecialchars($ep['last_checked'], ENT_QUOTES, 'UTF-8') : 'Never'; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>