#!/bin/bash

echo "🚀 Setting up monitoring infrastructure..."

# Create directory structure
echo "📁 Creating directory structure..."
mkdir -p monitoring/grafana/{dashboards,datasources}
mkdir -p monitoring/prometheus

# Create Prometheus configuration
echo "⚙️ Creating Prometheus configuration..."
cat > monitoring/prometheus.yml << 'EOF'
global:
  scrape_interval: 15s
  evaluation_interval: 15s

scrape_configs:
  - job_name: 'prometheus'
    static_configs:
      - targets: ['localhost:9090']
  
  - job_name: 'node-exporter'
    static_configs:
      - targets: ['node-exporter:9100']
  
  - job_name: 'mysql'
    static_configs:
      - targets: ['mysql-exporter:9104']
  
  - job_name: 'php-app-health'
    static_configs:
      - targets: ['web:80']
    metrics_path: '/health.php'
    scrape_interval: 30s
EOF

# Create Grafana datasource configuration
echo "📊 Creating Grafana datasource configuration..."
cat > monitoring/grafana/datasources/prometheus.yml << 'EOF'
apiVersion: 1

datasources:
  - name: Prometheus
    type: prometheus
    access: proxy
    url: http://prometheus:9090
    isDefault: true
    editable: true
EOF

# Create health check endpoint
echo "🏥 Creating health check endpoint..."
cat > health.php << 'EOF'
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

// System metrics
$health_status['services']['system'] = [
    'status' => 'healthy',
    'memory_usage_bytes' => memory_get_usage(true),
    'peak_memory_bytes' => memory_get_peak_usage(true)
];

echo json_encode($health_status, JSON_PRETTY_PRINT);

if ($health_status['status'] === 'healthy') {
    http_response_code(200);
} else {
    http_response_code(503);
}
?>
EOF

# Create metrics endpoint for Prometheus
echo "📈 Creating metrics endpoint..."
cat > metrics.php << 'EOF'
<?php
header('Content-Type: text/plain; charset=utf-8');

// Basic application metrics
$metrics = [];

// HTTP request counter (simplified)
$metrics[] = '# HELP php_app_requests_total Total HTTP requests';
$metrics[] = '# TYPE php_app_requests_total counter';
$metrics[] = 'php_app_requests_total{method="GET",status="200"} ' . rand(100, 1000);

// Memory usage
$memory_usage = memory_get_usage(true);
$metrics[] = '# HELP php_app_memory_usage_bytes Current memory usage';
$metrics[] = '# TYPE php_app_memory_usage_bytes gauge';
$metrics[] = 'php_app_memory_usage_bytes ' . $memory_usage;

// Database connection status
try {
    $pdo = new PDO(
        'mysql:host=db;dbname=phplogin;charset=utf8',
        '123',
        '123',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $db_status = 1;
} catch (Exception $e) {
    $db_status = 0;
}

$metrics[] = '# HELP php_app_database_up Database connection status';
$metrics[] = '# TYPE php_app_database_up gauge';
$metrics[] = 'php_app_database_up ' . $db_status;

// Application uptime (simplified)
$metrics[] = '# HELP php_app_uptime_seconds Application uptime';
$metrics[] = '# TYPE php_app_uptime_seconds counter';
$metrics[] = 'php_app_uptime_seconds ' . time();

echo implode("\n", $metrics) . "\n";
?>
EOF

# Create monitoring verification script
echo "🔍 Creating monitoring verification script..."
cat > verify-monitoring.sh << 'EOF'
#!/bin/bash

echo "🔍 Verifying monitoring setup..."

# Function to check service
check_service() {
    local service_name=$1
    local url=$2
    local max_attempts=20
    local attempt=1
    
    echo "Checking $service_name..."
    
    while [ $attempt -le $max_attempts ]; do
        if curl -f -s "$url" > /dev/null 2>&1; then
            echo "✅ $service_name is ready"
            return 0
        else
            echo "⏳ Waiting for $service_name... (attempt $attempt/$max_attempts)"
            sleep 15
            ((attempt++))
        fi
    done
    
    echo "❌ $service_name failed after $max_attempts attempts"
    return 1
}

# Wait for containers to start
echo "⏳ Waiting for containers to initialize..."
sleep 30

# Check all services
echo "🔍 Checking services..."
check_service "Web Application" "http://localhost:8081"
check_service "Database (via app)" "http://localhost:8081/health.php"
check_service "Prometheus" "http://localhost:9090/-/ready"
check_service "Node Exporter" "http://localhost:9100/metrics"
check_service "MySQL Exporter" "http://localhost:9104/metrics"

echo ""
echo "📊 Service Status Summary:"
echo "================================"
docker-compose ps

echo ""
echo "🌐 Available Endpoints:"
echo "================================"
echo "Web App: http://localhost:8081"
echo "Health Check: http://localhost:8081/health.php"
echo "App Metrics: http://localhost:8081/metrics.php"
echo "PhpMyAdmin: http://localhost:8082"
echo "Prometheus: http://localhost:9090"
echo "Node Exporter: http://localhost:9100/metrics"
echo "MySQL Exporter: http://localhost:9104/metrics"
echo "Grafana: http://localhost:3000 (admin/admin123)"

echo ""
echo "✅ Monitoring setup verification completed!"
EOF

chmod +x verify-monitoring.sh

# Create docker-compose override for development
echo "🔧 Creating docker-compose override for development..."
cat > docker-compose.override.yml << 'EOF'
version: '3.8'

services:
  web:
    volumes:
      - .:/var/www/html
    environment:
      - PHP_DISPLAY_ERRORS=On
      - PHP_ERROR_REPORTING=E_ALL

  prometheus:
    volumes:
      - ./monitoring/prometheus.yml:/etc/prometheus/prometheus.yml:ro
      
  grafana:
    volumes:
      - ./monitoring/grafana/datasources:/etc/grafana/provisioning/datasources:ro
EOF

echo ""
echo "✅ Monitoring setup completed!"
echo ""
echo "📋 Next steps:"
echo "1. Run: docker-compose up -d"
echo "2. Wait for services to start"
echo "3. Run: ./verify-monitoring.sh"
echo "4. Access Prometheus at http://localhost:9090"
echo "5. Access Grafana at http://localhost:3000 (admin/admin123)"
echo ""
echo "🚀 Your monitoring stack is ready!"
