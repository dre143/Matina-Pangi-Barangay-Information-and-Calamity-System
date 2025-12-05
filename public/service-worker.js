const CACHE_NAME = 'mpangi-cache-v3';
const OFFLINE_URL = '/offline.html';
const DB_NAME = 'mpangi-queue';
const STORE = 'requests';

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll([
      OFFLINE_URL,
      '/logo.png'
    ]))
  );
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  event.waitUntil(
    Promise.all([
      caches.keys().then(keys => Promise.all(keys.map(k => k !== CACHE_NAME ? caches.delete(k) : Promise.resolve()))),
      flushQueue().catch(() => {})
    ])
  );
  self.clients.claim();
});

self.addEventListener('message', event => {
  const data = event.data || {};
  if (data.type === 'enqueue' && data.payload) {
    event.waitUntil(enqueue(data.payload));
  }
  if (data.type === 'sync') {
    event.waitUntil(flushQueue());
  }
  if (data.type === 'clearCaches') {
    event.waitUntil(
      caches.keys().then(keys => Promise.all(keys.map(k => caches.delete(k)))).catch(() => {})
    );
  }
});

self.addEventListener('fetch', event => {
  const req = event.request;
  const url = new URL(req.url);
  if (req.method !== 'GET') {
    return;
  }
  if (req.mode === 'navigate') {
    event.respondWith(
      caches.match(req).then(cached => {
        return fetch(req).then(resp => {
          const ct = resp.headers.get('content-type') || '';
          const clone = resp.clone();
          if (resp.ok && ct.includes('text/html') && url.pathname !== '/login') {
            caches.open(CACHE_NAME).then(cache => cache.put(req, clone));
          }
          return resp;
        }).catch(() => cached || caches.match(OFFLINE_URL));
      })
    );
    return;
  }
  if (url.origin === self.location.origin) {
    event.respondWith(
      caches.match(req).then(cached => {
        const fetchPromise = fetch(req).then(resp => {
          const ct = resp.headers.get('content-type') || '';
          const clone = resp.clone();
          if (resp.ok && !ct.includes('text/html')) {
            caches.open(CACHE_NAME).then(cache => cache.put(req, clone));
          }
          return resp;
        }).catch(() => cached);
        return cached || fetchPromise;
      })
    );
  }
});

function openDb() {
  return new Promise((resolve, reject) => {
    const req = indexedDB.open(DB_NAME, 1);
    req.onupgradeneeded = () => {
      const db = req.result;
      if (!db.objectStoreNames.contains(STORE)) db.createObjectStore(STORE, { keyPath: 'id', autoIncrement: true });
    };
    req.onsuccess = () => resolve(req.result);
    req.onerror = () => reject(req.error);
  });
}

async function enqueue(payload) {
  const db = await openDb();
  return new Promise((resolve, reject) => {
    const tx = db.transaction(STORE, 'readwrite');
    tx.oncomplete = () => resolve();
    tx.onerror = () => reject(tx.error);
    const store = tx.objectStore(STORE);
    store.add({
      url: payload.url,
      method: payload.method || 'POST',
      body: payload.body || null,
      entries: payload.entries || null,
      enctype: payload.enctype || null,
      contentType: payload.contentType || null,
      createdAt: Date.now()
    });
  });
}

async function allQueued() {
  const db = await openDb();
  return new Promise((resolve, reject) => {
    const tx = db.transaction(STORE, 'readonly');
    tx.onerror = () => reject(tx.error);
    const store = tx.objectStore(STORE);
    const req = store.getAll();
    req.onsuccess = () => resolve(req.result || []);
    req.onerror = () => reject(req.error);
  });
}

async function removeQueued(id) {
  const db = await openDb();
  return new Promise((resolve, reject) => {
    const tx = db.transaction(STORE, 'readwrite');
    tx.oncomplete = () => resolve();
    tx.onerror = () => reject(tx.error);
    tx.objectStore(STORE).delete(id);
  });
}

async function flushQueue() {
  const items = await allQueued();
  for (const item of items) {
    try {
      let body;
      let headers = {};
      if (item.entries && item.enctype === 'multipart/form-data') {
        const fd = new FormData();
        item.entries.forEach(([k, v]) => fd.append(k, v));
        body = fd;
      } else if (item.entries) {
        const params = new URLSearchParams();
        item.entries.forEach(([k, v]) => params.append(k, v));
        body = params.toString();
        headers['Content-Type'] = 'application/x-www-form-urlencoded';
      } else if (item.body) {
        body = item.body;
        if (item.contentType) headers['Content-Type'] = item.contentType;
      }
      const resp = await fetch(item.url, { method: item.method || 'POST', body, headers, credentials: 'include' });
      if (resp && resp.ok) await removeQueued(item.id);
    } catch (e) {}
  }
}
