const http = require('node:http');
const mysql = require('mysql2/promise');

const port = Number(process.env.PORT || 3000);
const pool = mysql.createPool({
  host: process.env.DB_HOST || 'db',
  port: Number(process.env.DB_PORT || 3306),
  user: process.env.DB_USER || 'ud03',
  password: process.env.DB_PASSWORD || 'ud03_local_only',
  database: process.env.DB_NAME || 'ud03',
  waitForConnections: true,
  connectionLimit: 5
});

const server = http.createServer(async (req, res) => {
  if (req.url === '/health') {
    try {
      await pool.query('SELECT 1');
      res.writeHead(200, { 'content-type': 'application/json; charset=utf-8' });
      res.end(JSON.stringify({ status: 'ok', app: 'UD03_E2 Node', database: 'connected' }));
    } catch {
      res.writeHead(503, { 'content-type': 'application/json; charset=utf-8' });
      res.end(JSON.stringify({ status: 'unavailable', database: 'disconnected' }));
    }
    return;
  }
  const host = String(req.headers.host || 'ud03.localhost').replace(/[&<>\"']/g, (char) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[char]);
  res.writeHead(200, { 'content-type': 'text/html; charset=utf-8' });
  res.end(`<!doctype html><html lang="es"><meta charset="utf-8"><title>UD03_E2</title><h1>UD03_E2 · despliegue Node 20</h1><p>Host atendido por Apache como proxy inverso: ${host}</p></html>`);
});

if (require.main === module) {
  server.listen(port, '0.0.0.0', () => console.log(`UD03_E2 Node escuchando en ${port}`));
  process.on('SIGTERM', shutdown);
  process.on('SIGINT', shutdown);
}

async function shutdown() {
  if (server.listening) await new Promise((resolve) => server.close(resolve));
  await pool.end();
}

module.exports = { server, shutdown };
