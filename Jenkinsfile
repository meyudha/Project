pipeline {
    agent any
    tools {
        nodejs 'nodejs' // Name from Global Tools config
    }
    
    environment {
        DEPLOY_TARGET = '/var/www/your-project'
        SSH_CREDENTIALS = credentials('deploy-ssh-key')
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/meyudha/Project.git'
            }
        }
        
        stage('Install Dependencies') {
            steps {
                sh 'composer install --no-interaction --optimize-autoloader'
                sh 'npm ci'
            }
        }
        
        stage('Build Assets') {
            steps {
                sh 'npm run build' // Your build command (e.g., webpack, vite)
            }
        }
        
        stage('Run Tests') {
            steps {
                sh 'vendor/bin/phpunit' // PHP tests
                sh 'npm test' // JS tests (if any)
            }
        }
        
        stage('Deploy') {
            steps {
                sshagent([SSH_CREDENTIALS]) {
                    sh """
                    rsync -avz --delete \
                        --exclude='.git' \
                        --exclude='node_modules' \
                        --exclude='.env' \
                        -e ssh ./ user@server:${DEPLOY_TARGET}
                    """
                    sh "ssh user@server 'cd ${DEPLOY_TARGET} && php artisan optimize'"
                }
            }
        }
    }
    
}