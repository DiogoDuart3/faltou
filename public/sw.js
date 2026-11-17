const CACHE_NAME = 'faltou-v3';
const CORE_ASSETS = ['/', '/falta-eletricidade', '/falta-agua'];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(CORE_ASSETS)).then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) => Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') {
        return;
    }
    const requestUrl = new URL(event.request.url);
    if (requestUrl.origin !== self.location.origin) {
        return;
    }

    const fromNetwork = () =>
        fetch(event.request).then((response) => {
            if (response && response.status === 200) {
                const cloned = response.clone();
                caches.open(CACHE_NAME).then((cache) => cache.put(event.request, cloned));
            }
            return response;
        });

    // Pages: network first, so a new deploy is never hidden behind a stale cached page.
    // The cache is only the fallback when the network fails.
    if (event.request.mode === 'navigate') {
        event.respondWith(fromNetwork().catch(() => caches.match(event.request)));
        return;
    }

    // Assets (fingerprinted by Vite): cache first, refreshed in the background.
    event.respondWith(
        caches.match(event.request).then((cached) => {
            const fetchPromise = fromNetwork().catch(() => cached);
            return cached || fetchPromise;
        })
    );
});
