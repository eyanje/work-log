pipeline {
    agent {
        kubernetes {
            cloud 'kubernetes'
            yaml '''
                spec:
                  containers:
                    - image: harbor.eyanje.net/work-log/build-agent:latest
                      name: jnlp
                      securityContext:
                        privileged: true
                        runAsUser: 1000
                  securityContext:
                    hostNetwork: true
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
                              name: base
                              command:
                                - sleep
                              args:
                                - 99d
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

