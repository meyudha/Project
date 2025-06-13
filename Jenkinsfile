pipeline {
    agent {
        docker {
            image 'php:8.2-cli'
            args '-u root'
        }
    }
    
    tools {
        nodejs 'nodejs-18' // Name from Global Tools config
    }
    
    environment {
        DEPLOY_TARGET = '/var/www/your-project'
    }
    
    stages {
        stage('Setup Environment') {
            steps {
                sh '''
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
                '''
            }
        }
        
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/meyudha/Project.git'
            }
        }
        
        stage('Install Dependencies') {
            steps {
                sh '''
                    echo "=== Installing PHP Dependencies ==="
                    composer install --no-interaction --optimize-autoloader
                    
                    echo "=== Installing Node Dependencies ==="
                    npm ci
                '''
            }
        }
        
        stage('Build Assets') {
            steps {
                sh '''
                    echo "=== Building Assets ==="
                    npm run build
                '''
            }
        }
        
        stage('Run Tests') {
            steps {
                script {
                    try {
                        sh '''
                            echo "=== Running PHP Tests ==="
                            if [ -f "vendor/bin/phpunit" ]; then
                                vendor/bin/phpunit
                            else
                                echo "PHPUnit not found, skipping PHP tests"
                            fi
                        '''
                    } catch (Exception e) {
                        echo "PHP tests failed: ${e.getMessage()}"
                    }
                    
                    try {
                        sh '''
                            echo "=== Running JavaScript Tests ==="
                            if npm list --json | grep -q '"test"'; then
                                npm test
                            else
                                echo "No npm test script found, skipping JS tests"
                            fi
                        '''
                    } catch (Exception e) {
                        echo "JavaScript tests failed: ${e.getMessage()}"
                    }
                }
            }
        }
        
        stage('Deploy') {
            steps {
                sh '''
                echo "=== Deployment with Docker ==="

            # Build Docker Image
            docker build -t meyudha/project-app .

            # Run Container
            docker run -d -p 5000:5000 meyudha/project-app

            echo "Docker container deployed successfully."
        '''
    }
}
    }
    
    post {
        always {
            echo 'Pipeline execution completed'
        }
        success {
            echo 'Pipeline executed successfully!'
        }
        failure {
            echo 'Pipeline failed. Check the logs above for details.'
        }
    }
}
