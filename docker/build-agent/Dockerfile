FROM docker.io/jenkins/inbound-agent:latest

USER root
RUN apt-get update && apt-get install -y \
    containers-storage \
    make \
    podman-docker

ADD --chown=root:root --chmod=755 \
    https://dl.k8s.io/release/v1.34.0/bin/linux/amd64/kubectl /usr/local/bin/kubectl

USER jenkins
