FROM node:20-alpine AS dependencies
WORKDIR /app
COPY package*.json ./
RUN npm install --omit=dev

FROM node:20-alpine AS production
ENV NODE_ENV=production PORT=3000
WORKDIR /app
COPY --from=dependencies /app/node_modules ./node_modules
COPY package.json ./
COPY src ./src
USER node
EXPOSE 3000
CMD ["node", "src/server.js"]
