FROM alpine:latest
RUN apt-get update && apt-get install -y supervisor
ADD ./supervisor/supervisord.conf /etc/supervisor/conf.d/
CMD ["/usr/bin/supervisord"]