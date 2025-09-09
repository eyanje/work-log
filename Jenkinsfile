pipeline {
    agent {
        kubernetes {
            cloud 'kubernetes'
            yaml readFile('jenkins/build-agent.yaml')
        }
    }

    stages {
        stage('Create build cache') {
            agent {
                kubernetes {
                    cloud 'kubernetes'
                    yaml readFile('jenkins/build-agent-minimal.yaml')
                }
            }
            steps {
                echo 'Creating build cache volume'
                withKubeConfig(credentialsId: 'work-log-kube-config', namespace: 'default', restrictKubeConfigAccess: true) {
                    sh 'kubectl create -f jenkins/build-cache.yaml'
                }
            }
        }

        stage('Build') {
            agent {
                kubernetes {
                    cloud 'kubernetes'
                    yaml readFile('jenkins/build-agent.yaml')
                }
            }

            steps {
                echo 'Building'
                withCredentials([usernamePassword(credentialsId: 'work-log-registry', passwordVariable: 'PASSWORD', usernameVariable: 'USERNAME')]) {
                    sh 'docker login -u=$USERNAME -p=$PASSWORD harbor.eyanje.net'
                }
                sh 'make -j 4'
            }
        }
        stage('Test') {
            agent {
                kubernetes {
                    cloud 'kubernetes'
                    yaml readFile('jenkins/build-agent.yaml')
                }
            }
            steps {
                echo 'Test'
                sh 'make test'
            }
        }
        stage('Publish') {
            agent {
                kubernetes {
                    cloud 'kubernetes'
                    yaml readFile('jenkins/build-agent.yaml')
                }
            }
            steps {
                echo 'Publishing'
                withCredentials([usernamePassword(credentialsId: 'work-log-registry', passwordVariable: 'PASSWORD', usernameVariable: 'USERNAME')]) {
                    sh 'docker login -u=$USERNAME -p=$PASSWORD harbor.eyanje.net'
                }
                sh 'make push -j 8'
            }
        }
        stage('Deploy') {
            agent {
                kubernetes {
                    cloud 'kubernetes'
                    yaml readFile('jenkins/build-agent.yaml')
                }
            }
            environment {
                ENV_FILE = credentials('work-log-environment')
            }
            steps {
                echo 'Deploying'
                withKubeConfig(credentialsId: 'work-log-kube-config', namespace: 'default', restrictKubeConfigAccess: true) {
                    sh './scripts/deploy.sh'
                }
            }
        }
    }
}


