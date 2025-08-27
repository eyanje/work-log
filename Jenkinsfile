pipeline {
    agent {
        docker { image 'docker.io/alpine:latest' }
    }
	agent any

	stages {
		stage('Build') {
			steps {
				echo 'Building'
				sh './scripts/build.sh'
			}
		}
		stage('Test') {
            agent {
                docker { image 'harbor.eyanje.net/work-log/base:latest' }
            }
			steps {
				echo 'Test'
				sh 'php artisan test'
			}
		}
		stage('Publish') {
			steps {
				echo 'Publishing'
				sh './scripts/publish.sh'
			}
		}
		stage('Deploy') {
            environment {
                ENV_FILE =
                credentials('work-log-production-environment')
            }
			steps {
				echo 'Deploying'
				ansiblePlaybook './scripts/deploy.sh'
			}
		}
	}
}
