FROM nginx:latest
MAINTAINER Sven Gehring <cbrxde@gmail.com>

RUN rm /etc/nginx/conf.d/default.conf
COPY ./webserver.conf /etc/nginx/conf.d/webserver.conf
