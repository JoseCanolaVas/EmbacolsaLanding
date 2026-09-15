FROM node:18-alpine

WORKDIR /app

COPY apps/frontend/package*.json ./

RUN npm ci

COPY apps/frontend/ ./

ARG API_URL=/api
ARG BACKEND_URL=http://backend

ENV API_URL=${API_URL}
ENV BACKEND_URL=${BACKEND_URL}

RUN npm run generate

ENV NODE_ENV=production
ENV HOST=0.0.0.0
ENV PORT=3000

EXPOSE 3000

CMD ["npm", "run", "start"]