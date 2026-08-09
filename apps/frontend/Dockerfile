FROM node:20-alpine AS build

WORKDIR /app

ARG API_URL=/api
ENV API_URL=${API_URL}

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run generate

FROM nginx:1.27-alpine

COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=build /app/dist /usr/share/nginx/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
