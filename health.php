<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$health_status = [
    'status' => 'healthy',
    'timestamp' => date('Y-m-d H:i:s'),
    'services' => []
];

// Check database connection
try {
    $pdo = new PDO(
        'mysql:host=db;dbname=phplogin;charset=utf8',
        '123',
        '123',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Test database with simple query
    $stmt = $pdo->query('SELECT 1');
    $result = $stmt->fetch();
    
    if ($result) {
        $health_status['services']['database'] = [
            'status' => 'healthy',
            'response_time_ms' => 0
        ];
    } else {
        throw new Exception('Database query failed');
    }
    
} catch (Exception $e) {
    $health_status['status'] = 'unhealthy';
    $health_status['services']['database'] = [
        'status' => 'unhealthy',
        'error' => $e->getMessage()
    ];
    http_response_code(503);
}

// Check disk space (optional)
$disk_free = disk_free_space('/var/www/html');
$disk_total = disk_total_space('/var/www/html');
$disk_usage_percent = (1 - ($disk_free / $disk_total)) * 100;

$health_status['services']['filesystem'] = [
    'status' => $disk_usage_percent < 90 ? 'healthy' : 'warning',
    'disk_usage_percent' => round($disk_usage_percent, 2)
];

// Check memory usage (optional)
$memory_usage = memory_get_usage(true);
$memory_peak = memory_get_peak_usage(true);

$health_status['services']['memory'] = [
    'status' => 'healthy',
    'current_usage_bytes' => $memory_usage,
    'peak_usage_bytes' => $memory_peak
];

// Return health status
echo json_encode($health_status, JSON_PRETTY_PRINT);

// Set appropriate HTTP status code
if ($health_status['status'] === 'healthy') {
    http_response_code(200);
} else {
    http_response_code(503);
}
?>
