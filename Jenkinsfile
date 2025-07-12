pipeline {
	agent any

	stages {
		stage('Build') {
			steps {
				echo 'Building'
				sh './scripts/install.sh'
			}
		}
		stage('Deploy') {
			steps {
				echo 'Deploying'
			}
		}
	}
}
