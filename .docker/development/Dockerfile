FROM registry.gitlab.com/hyper-coder-labs/infra/docker-php-runtimes/php-dev-8.2-swoole:latest

ARG UID=1000
ARG GID=1000

ENV USER_NAME=rsync-user
ENV GROUP_NAME=rsync-user

WORKDIR /opt/www

RUN chmod +x /usr/local/bin/composer
RUN chmod +x /usr/local/bin/co-phpunit

RUN addgroup -g $GID $GROUP_NAME && \
    adduser --shell /sbin/nologin --disabled-password \
    --uid $UID --ingroup $GROUP_NAME $USER_NAME

USER $USER_NAME