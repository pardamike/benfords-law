FROM webdevops/php-nginx:8.2-alpine as app
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
RUN cp .env.example .env
RUN pwd
RUN composer install --no-interaction --no-cache

FROM node:18 as node
WORKDIR /usr/app
COPY --from=app /app/. /usr/app
RUN npm install \
    && npm run build

FROM app
COPY --from=node /usr/app/public/. public/
RUN chmod -R 777 /app/storage
ENV WEB_DOCUMENT_ROOT="/app/public"
