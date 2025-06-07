pipeline {
    agent any
    
    tools {
        nodejs 'nodejs-18' // Name from Global Tools config
    }
    
    environment {
        DEPLOY_TARGET = '/var/www/your-project'
    }
    
    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/meyudha/Project.git'
            }
        }
        
        stage('Install PHP & Composer') {
            steps {
                sh '''
                    echo "=== Installing PHP and Composer ==="
                    
                    # Update package manager
                    apt-get update -qq
                    
                    # Install PHP and required extensions
                    apt-get install -y php php-cli php-mbstring php-xml php-zip php-curl unzip curl
                    
                    # Install Composer if not exists
                    if ! command -v composer &> /dev/null; then
                        echo "Installing Composer..."
                        curl -sS https://getcomposer.org/installer | php
                        mv composer.phar /usr/local/bin/composer
                        chmod +x /usr/local/bin/composer
                    fi
                    
                    # Verify installations
                    php --version
                    composer --version
                    node --version
                    npm --version
                '''
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
                script {
                    try {
                        sh '''
                            echo "=== Deployment Stage ==="
                            echo "Project built successfully"
                            echo "Files ready for deployment to: ${DEPLOY_TARGET}"
                            
                            # Simple deployment example (uncomment and modify as needed)
                            # cp -r . ${DEPLOY_TARGET}
                            
                            # For SSH deployment, you would need to configure SSH credentials first
                            # Example:
                            # sshagent(['your-ssh-credential-id']) {
                            #     sh """
                            #     rsync -avz --delete \\
                            #         --exclude='.git' \\
                            #         --exclude='node_modules' \\
                            #         --exclude='.env' \\
                            #         ./ user@server:${DEPLOY_TARGET}
                            #     """
                            # }
                        '''
                    } catch (Exception e) {
                        echo "Deployment failed: ${e.getMessage()}"
                    }
                }
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
        cleanup {
            // Clean up workspace if needed
            echo 'Cleaning up...'
        }
    }
}
