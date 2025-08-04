<?php
if (php_sapi_name() !== 'cli') {
    echo "This script must be run from the command line." . PHP_EOL;
    exit(1);
}

require_once __DIR__ . '/../includes/database.php';

function checkEndpointStatus(string $url): string
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_NOBODY         => false,
        CURLOPT_RANGE          => '0-0',
        CURLOPT_HEADER         => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123 Safari/537.36',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        ],
    ]);

    $headers  = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (in_array($httpCode, [403, 503]) && stripos($headers, 'cf-ray') !== false) {
        return 'up';
    }

    return ($httpCode >= 200 && $httpCode < 400) ? 'up' : 'down';
}

$db  = new Database();
$conn = $db->connect();
if (!$conn) {
    fwrite(STDERR, "[ERROR] Database connection failed\n");
    exit(1);
}

$stmt = $db->query("SELECT * FROM endpoints WHERE last_checked IS NULL OR TIMESTAMPDIFF(SECOND, last_checked, NOW()) >= interval_seconds");
$rows = $stmt ? $stmt->fetchAll() : [];

foreach ($rows as $ep) {
    $status = checkEndpointStatus($ep['url']);
    $db->query('UPDATE endpoints SET last_checked = NOW(), last_status = ? WHERE id = ?', [$status, $ep['id']]);
    echo sprintf("[%s] %s => %s\n", date('Y-m-d H:i:s'), $ep['url'], strtoupper($status));
}