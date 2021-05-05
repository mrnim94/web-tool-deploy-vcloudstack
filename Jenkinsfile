pipeline {

    environment {
        nameImage = "docker.nimtechnology.com/tool/web"
        privateRegistry = "https://docker.nimtechnology.com"
        registryCredential = "docker_nimtechnology"
    }

    agent any
    
    parameters {
        gitParameter name: 'BRANCH', 
            type: 'PT_BRANCH',
            defaultValue: 'master'
    }
    
    stages {
        stage('Check GIT') {
            steps {
                checkout([$class: 'GitSCM', 
                    branches: [[name: "${params.BRANCH}"]], 
                    doGenerateSubmoduleConfigurations: false, 
                    extensions: [], 
                    gitTool: 'Default', 
                    submoduleCfg: [], 
                    userRemoteConfigs: [[credentialsId: 'Jenkin-login-Gitlab', url: 'https://gitlab.nimtechnology.com/nim/web-tool-deploy-vcloudstack.git']]
                ])
            }
        }
        
        stage('Prepare ENV for Build') {
            steps {
                script {
                    IMAGE_NAME = "docker.nimtechnology.com/tool/web"
                    GIT_BRANCH = sh(returnStdout: true, script: "git rev-parse --abbrev-ref HEAD").trim()
                    GIT_HASH = sh(returnStdout: true, script: "git rev-parse --short HEAD").trim()
                }
            //echo "branch is: ${env.GIT_BRANCH}"
            //echo "hash is: ${GIT_HASH}"
            //echo "hash is: ${IMAGE_NAME}"
			sh "echo GIT_HASH=${GIT_HASH} > trigger.properties"
			sh "echo ACTION=DEPLOY >> trigger.properties"
			sh "echo TAG=1.5-SNAPSHOT >> trigger.properties"
			archiveArtifacts 'trigger.properties'
            }
        }
        
        stage('Create images') {
            steps {
                // sh "docker build -t ${IMAGE_NAME}:latest ."
                // sh "docker build -t ${IMAGE_NAME}:${GIT_HASH} ."
                script {
                  app_latest = docker.build nameImage + ":latest"
                  app_version = docker.build nameImage + ":$GIT_HASH"
                }
            }
        }

        stage('Push Image Private HUB Docker') {
            steps{
                script {
                    docker.withRegistry( privateRegistry, registryCredential ) {
                        app_latest.push()
                        app_version.push()
                    }
                }
            }
        }

        stage('Remove Unused docker image') {
            steps{
                sh "docker rmi $nameImage:latest"
                sh "docker rmi $nameImage:$GIT_HASH"
            }
        }

        stage('Remove Dangling Images') {
            steps{
                sh " docker system prune -f"
            }
        }
    }


//   stages {
//         stage('Pull source Code') {
//             steps {
//                 git credentialsId: 'bf3cc5b7-6d04-41da-a296-bd04dac16249', url: 'http://192.168.21.16/mrnim94/db_tool.git'
//             }
//         }
//         stage('Prepare ENV for Build') {
//             steps {
//                 script {
//                 env.GIT_BRANCH = sh(returnStdout: true, script: "git rev-parse --abbrev-ref HEAD").trim()
//                 GIT_HASH = sh(returnStdout: true, script: "git rev-parse --short HEAD").trim()
//                 }
//             echo "branch is: ${env.GIT_BRANCH}"
//             echo "hash is: ${GIT_HASH}"
//             }
//         }
//   }
}