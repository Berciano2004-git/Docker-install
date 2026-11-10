const test = require('node:test');
const assert = require('node:assert/strict');
const http = require('node:http');
const { once } = require('node:events');

process.env.DB_HOST = '127.0.0.1';
process.env.DB_PORT = '1';
const serverModule = require('../src/server');

test('la página muestra el nombre de host recibido y el endpoint health responde', async (t) => {
  const server = serverModule.server;
  server.listen(0, '127.0.0.1');
  await once(server, 'listening');
  t.after(() => serverModule.shutdown());
  const { port } = server.address();

  const page = await new Promise((resolve, reject) => {
    http.get({ hostname: '127.0.0.1', port, path: '/', headers: { host: 'ud03.localhost' } }, (res) => {
      let body = '';
      res.setEncoding('utf8');
      res.on('data', (chunk) => { body += chunk; });
      res.on('end', () => resolve({ status: res.statusCode, body }));
    }).on('error', reject);
  });
  assert.equal(page.status, 200);
  assert.match(page.body, /ud03\.localhost/);
  assert.match(page.body, /UD03_E2/);

  const health = await new Promise((resolve, reject) => {
    const request = http.get({ hostname: '127.0.0.1', port, path: '/health' }, (res) => {
      res.resume();
      res.on('end', () => resolve(res.statusCode));
    });
    request.setTimeout(1000, () => request.destroy(new Error('timeout')));
    request.on('error', reject);
  });
  assert.equal(health, 503);
});
