pipeline {
    agent {
        kubernetes {
            cloud 'kubernetes'
            yaml '''
                spec:
                  containers:
                    - image: docker.io/alpine:latest
                      name: alpine
                '''
        }
    }

	stages {
		stage('Build') {
			steps {
				echo 'Building'
				sh './scripts/build.sh'
			}
		}
		stage('Test') {
            agent {
                kubernetes {
                    cloud 'kubernetes'
                    yaml '''
                        spec:
                          containers:
                            - image: harbor.eyanje.net/work-log-base:latest
                              imagePullPolicy: Never
                              name: alpine
                        '''
                }
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
