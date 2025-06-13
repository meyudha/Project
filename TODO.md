# JANLUP DOKUMENTASI
## Dependencies
> [!IMPORTANT]
> Sebelum Mulai, Instal beberapa paket dibawah terlebih dahulu
* apache2
```
sudo apt install apache2
```
* mySQL
```
sudo apt install mysql-server
```
* php
```
sudo apt install php
sudo apt install php-mysql
```
* Docker Engine
1. Instal preReq
```
# Add Docker's official GPG key:
sudo apt-get update
sudo apt-get install ca-certificates curl
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc

# Add the repository to Apt sources:
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "${UBUNTU_CODENAME:-$VERSION_CODENAME}") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
```
  2. Instal Docker
```
sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```
  3. Test Docker
```
sudo docker run hello-world
```

## BUILD
### Script
Instal npm
```
sudo apt install npm
```
Instal front dependencies npm
```
npm install bootstrap@5 jquery@3
```
Inisialisasi npm
```
npm init -y
```
di dalam <b>package.json</b>, masukkan baris kode ini ke dalam bagian "scripts":
```
"build": "mkdir -p public/css public/js && cp node_modules/bootstrap/dist/css/bootstrap.min.css public/css/ && cp node_modules/bootstrap/dist/js/bootstrap.bundle.min.js public/js/ && cp node_modules/jquery/dist/jquery.min.js public/js/",
"start": "npm run build && echo 'Project built! Run your PHP server manually'"
```
Masukkan kode ini kedalam bagian header pada setiap php yang mengandung html
```
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="/css/bootstrap.min.css">
```
Masukkan kode ini kedalam bagian body pada setiap php yang mengandung html
```
<!-- jQuery & Bootstrap JS -->
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
```
Build
```
npm run build
```
### Konfigurasi mysqli-connect
buka file <b>php.ini</b> dengan privilege root (disarankan pakai aplikasi text editor seperti sublime-text)
```
# Pilih salah satu, tergantung versi php yang terinstal
sudo subl /etc/php/8.1/cli/php.ini
sudo subl /etc/php/8.3/cli/php.ini
```
Carilah baris kode *;extension=mysqli* (pada kasus ini, berada di line 930) lalu hapus komentar (;) yang berada di depan kode tersebut, sehingga hasil akhir akan terlihat seperti ini
```
;extension=mbstring
;extension=exif      ; Must be after mbstring as it depends on it
extension=mysqli
;extension=oci8_12c  ; Use with Oracle Database 12c Instant Client
;extension=oci8_19  ; Use with Oracle Database 19 Instant Client
```
Restart apache2 service
```
systemctl restart apache2
```
### Konfigurasi hak akses session
Defaultnya, session akan tersimpan di
```
/var/lib/php/sessions
```
Ubah hak akses direktori tersebut menggunakan command
```
sudo chown -R www-data:www-data /var/lib/php/sessions
```
### Start 
Start aplikasi
```
sudo php -S localhost:8081
```
Pindah ke http://localhost:8081

## Test
Instal OWASP ZAP nya gimana
```
git clone https://github.com/zaproxy/zaproxy.git
```

### Konfigurasi Jenkins
Cloned Repository Ke Local
```
git clone https://github.com/meyudha/project.git
```
Navigasi ke Cloned Directory
```
cd project
```
Menjalankan jenkins dengan docker compose
```
git clone https://github.com/jenkins-docs/quickstart-tutorials.git
```
Navigasi ke quickstart-tutorials dan jalankan command ini
```
cd quickstart-tutorials
docker-compose --profile node up -d
```
Akses http://localhost:8080  
> [!NOTE]
> lihat password dengan menggunakan command `docker logs <container_id>`  
> Container id docker adalah text berformat hex rubbish setelah run jenkins

## Deploy
### Membuat Job Baru 
- Pada UI Jenkins, Klik **New Item** pada sidebar kiri
- Pilih **pipeline** dan berikan nama job "Project"
- Klik **Ok**
### Konfigurasi Pipeline 
- Cari Bagian **Pipeline** Section
- Dibawah **Definition**, Pilih **Pipeline script from SCM**
- Di **SCM**, Pilih **Git** dan tambahkan Repository URL
- Pastikan Branch specifier sesuai dengan branch pada github (pada kasus ini branch ada di main)
```
https://github.com/meyudha/project.git
```
- Save Job
### Update Isi dari 'workflow.yaml' untuk GitHub Action
```
name: CI/CD Pipeline with Docker

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

env:
  DEPLOY_TARGET: '/var/www/your-project'
  DOCKER_IMAGE: 'meyudha/project-app'

jobs:
  build-and-deploy:
    runs-on: ubuntu-latest
    
    container:
      image: php:8.2-cli
      options: --user root

    services:
      docker:
        image: docker:dind
        options: --privileged

    steps:
    - name: Setup Environment
      run: |
        echo "=== Setting up Environment ==="
        
        # Install required packages including Docker
        apt-get update
        apt-get install -y git unzip curl nodejs npm docker.io
        
        # Install Composer to current directory first
        curl -sS https://getcomposer.org/installer | php
        # Move to a writable location
        cp composer.phar /usr/local/bin/composer
        chmod +x /usr/local/bin/composer
        
        # Verify installations
        php --version
        composer --version
        node --version
        npm --version
        docker --version

    - name: Checkout code
      uses: actions/checkout@v4

    - name: Install Dependencies
      run: |
        echo "=== Installing PHP Dependencies ==="
        composer install --no-interaction --optimize-autoloader
        
        echo "=== Installing Node Dependencies ==="
        npm ci

    - name: Build Assets
      run: |
        echo "=== Building Assets ==="
        npm run build

    - name: Run PHP Tests
      continue-on-error: true
      run: |
        echo "=== Running PHP Tests ==="
        if [ -f "vendor/bin/phpunit" ]; then
          vendor/bin/phpunit
        else
          echo "PHPUnit not found, skipping PHP tests"
        fi

    - name: Run JavaScript Tests
      continue-on-error: true
      run: |
        echo "=== Running JavaScript Tests ==="
        if npm list --json | grep -q '"test"'; then
          npm test
        else
          echo "No npm test script found, skipping JS tests"
        fi

    - name: Deploy with Docker
      run: |
        echo "=== Deployment with Docker ==="
        # Build Docker Image
        docker build -t ${{ env.DOCKER_IMAGE }} .
        # Run Container
        docker run -d -p 5000:5000 ${{ env.DOCKER_IMAGE }}
        echo "Docker container deployed successfully."

    - name: Pipeline Status
      if: always()
      run: |
        echo 'Pipeline execution completed'
        if [ "${{ job.status }}" == "success" ]; then
          echo 'Pipeline executed successfully!'
        else
          echo 'Pipeline failed. Check the logs above for details.'
        fi

---
# Alternative version using separate jobs for better performance
name: CI/CD Pipeline with Docker (Optimized)

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

env:
  DEPLOY_TARGET: '/var/www/your-project'
  DOCKER_IMAGE: 'meyudha/project-app'

jobs:
  setup-and-test:
    runs-on: ubuntu-latest
    
    container:
      image: php:8.2-cli
      options: --user root

    steps:
    - name: Setup Environment
      run: |
        echo "=== Setting up Environment ==="
        
        # Install required packages
        apt-get update
        apt-get install -y git unzip curl nodejs npm
        
        # Install Composer
        curl -sS https://getcomposer.org/installer | php
        mv composer.phar /usr/local/bin/composer
        chmod +x /usr/local/bin/composer
        
        # Verify installations
        php --version
        composer --version
        node --version
        npm --version

    - name: Checkout code
      uses: actions/checkout@v4

    - name: Cache Composer dependencies
      uses: actions/cache@v3
      with:
        path: vendor
        key: composer-${{ hashFiles('composer.lock') }}
        restore-keys: composer-

    - name: Cache Node modules
      uses: actions/cache@v3
      with:
        path: node_modules
        key: node-${{ hashFiles('package-lock.json') }}
        restore-keys: node-

    - name: Install Dependencies
      run: |
        echo "=== Installing PHP Dependencies ==="
        composer install --no-interaction --optimize-autoloader
        
        echo "=== Installing Node Dependencies ==="
        npm ci

    - name: Build Assets
      run: |
        echo "=== Building Assets ==="
        npm run build

    - name: Run PHP Tests
      continue-on-error: true
      run: |
        echo "=== Running PHP Tests ==="
        if [ -f "vendor/bin/phpunit" ]; then
          vendor/bin/phpunit
        else
          echo "PHPUnit not found, skipping PHP tests"
        fi

    - name: Run JavaScript Tests
      continue-on-error: true
      run: |
        echo "=== Running JavaScript Tests ==="
        if npm list --json | grep -q '"test"'; then
          npm test
        else
          echo "No npm test script found, skipping JS tests"
        fi

    - name: Upload build artifacts
      uses: actions/upload-artifact@v3
      with:
        name: build-files
        path: |
          vendor/
          node_modules/
          public/build/
        retention-days: 1

  deploy:
    needs: setup-and-test
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'

    steps:
    - name: Checkout code
      uses: actions/checkout@v4

    - name: Download build artifacts
      uses: actions/download-artifact@v3
      with:
        name: build-files

    - name: Set up Docker Buildx
      uses: docker/setup-buildx-action@v2

    - name: Login to Docker Hub (Optional)
      if: github.event_name == 'push'
      uses: docker/login-action@v2
      with:
        username: ${{ secrets.DOCKER_USERNAME }}
        password: ${{ secrets.DOCKER_PASSWORD }}

    - name: Build and Deploy Docker Container
      run: |
        echo "=== Building Docker Image ==="
        docker build -t ${{ env.DOCKER_IMAGE }}:latest .
        
        echo "=== Deploying Container ==="
        # Stop existing container if running
        docker stop project-app-container || true
        docker rm project-app-container || true
        
        # Run new container
        docker run -d --name project-app-container -p 5000:5000 ${{ env.DOCKER_IMAGE }}:latest
        
        echo "Docker container deployed successfully on port 5000"

    - name: Push to Docker Hub (Optional)
      if: github.event_name == 'push'
      run: |
        docker push ${{ env.DOCKER_IMAGE }}:latest

  cleanup:
    needs: [setup-and-test, deploy]
    runs-on: ubuntu-latest
    if: always()
    
    steps:
    - name: Pipeline Status
      run: |
        echo 'Pipeline execution completed'
        if [ "${{ needs.deploy.result }}" == "success" ]; then
          echo 'Pipeline executed successfully!'
        else
          echo 'Pipeline completed with issues. Check individual job logs.'
        fi
```
### Hasil Setelah Dilakukan Deploy
![image](https://github.com/user-attachments/assets/7b17a7dd-a0ec-4a7d-9321-c72fd8baaeb7)


## Operate
Instal dockernya gimana
```

```

## Monitor
Buat user untuk Prometheus
```
sudo useradd --no-create-home --shell /bin/false prometheus
```
Buat direktori untuk Prometheus
```
sudo mkdir /etc/prometheus
sudo mkdir /var/lib/prometheus
```
Set ownership
```
sudo chown prometheus:prometheus /etc/prometheus
sudo chown prometheus:prometheus /var/lib/prometheus
```
Download Prometheus
```
cd /tmp
wget https://github.com/prometheus/prometheus/releases/download/v2.40.0/prometheus-2.40.0.linux-amd64.tar.gz
```
Extract file
```
tar xvf prometheus-2.40.0.linux-amd64.tar.gz
cd prometheus-2.40.0.linux-amd64
```
Buat file konfigurasi prometheus.yml
```
```
Buat service file
```
```
Reload systemd
```
sudo systemctl daemon-reload
```
Start prometheus
```
sudo systemctl start prometheus
```
Enable auto start
```
sudo systemctl enable prometheus
```
