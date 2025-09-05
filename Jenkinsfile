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
				withCredentials([usernamePassword(credentialsId: 'work-log-registry', passwordVariable: 'PASSWORD', usernameVariable: 'USERNAME')]) {
    				sh 'docker login -u=$USERNAME -p=$PASSWORD harbor.eyanje.net'
				}
				sh './scripts/build.sh --registry-cache'
			}
		}
		stage('Test') {
			steps {
				echo 'Test'
				sh './scripts/test.sh'
			}
		}
		stage('Publish') {
			steps {
				echo 'Publishing'
				withCredentials([usernamePassword(credentialsId: 'work-log-registry', passwordVariable: 'PASSWORD', usernameVariable: 'USERNAME')]) {
    				sh 'docker login -u=$USERNAME -p=$PASSWORD harbor.eyanje.net'
				}
				sh './scripts/publish.sh'
			}
		}
		stage('Deploy') {
            environment {
                ENV_FILE = credentials('work-log-environment')
            }
			steps {
				echo 'Deploying'
				withKubeConfig(credentialsId: 'work-log-kube-config', restrictKubeConfigAccess: true) {
				    sh './scripts/deploy.sh'
				}
			}
		}
	}
}


