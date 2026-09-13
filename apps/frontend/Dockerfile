FROM node:18-alpine

WORKDIR /app

COPY apps/frontend/package*.json ./

RUN npm ci

COPY apps/frontend/ ./

RUN npm run build

ENV NODE_ENV=production
ENV HOST=0.0.0.0
ENV PORT=3000

EXPOSE 3000

CMD ["npm", "run", "start"]