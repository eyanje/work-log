FROM docker.io/jenkins/inbound-agent:latest

USER root
RUN apt update && apt install -y \
    podman-docker

USER jenkins
