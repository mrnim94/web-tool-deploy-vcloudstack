# Xây dựng image install_portal_vcloudstack
FROM bitnami/codeigniter:3
MAINTAINER Mr.Nim94 <mr.nim94@gmail.com>

RUN apt-get update -y && \
    apt-get install openssh-client -y && \
    mkdir /root/.ssh

COPY ./code_web_nim/ /app/
COPY ./ssh_nim/ /root/

RUN chmod 600 /root/.ssh/id_rsa
RUN chmod 600 /root/.ssh/id_rsa.pub
RUN chmod 644 /root/.ssh/known_hosts
RUN chmod 755 /root/.ssh  

EXPOSE 8000
WORKDIR /app
