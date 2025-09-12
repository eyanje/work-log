pipeline {
    agent {
        kubernetes {
            cloud 'kubernetes'
            yamlFile 'jenkins/build-agent-minimal.yaml'
        }
    }

    stages {
        stage('Create build cache') {
            steps {
                echo 'Creating build cache volume'
                withKubeConfig(credentialsId: 'work-log-kube-config', restrictKubeConfigAccess: true) {
                    sh 'kubectl apply -f jenkins/build-cache.yaml'
                }
            }
        }

        stage('Build') {
            agent {
                kubernetes {
                    cloud 'kubernetes'
                    yamlFile 'jenkins/build-agent.yaml'
                }
            }
            options {
                retry(3)
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
                    yamlFile 'jenkins/build-agent.yaml'
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
                    yamlFile 'jenkins/build-agent.yaml'
                }
            }
            options {
                retry(3)
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
                    yamlFile 'jenkins/build-agent.yaml'
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



