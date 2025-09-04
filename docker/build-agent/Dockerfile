FROM docker.io/jenkins/inbound-agent:latest

USER root
RUN apt-get update && apt-get install -y \
    containers-storage \
    podman-docker

USER jenkins
