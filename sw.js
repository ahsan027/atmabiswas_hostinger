/*
 * ATMABISWAS — minimal offline-fallback service worker.
 *
 * Scope: only intercepts full-page navigations (not images/CSS/JS/API
 * calls) and, if the network request fails, serves a cached branded
 * "You're Offline" page instead of the browser's native connection-error
 * screen. This can only help visitors whose browser already installed
 * this worker on a prior successful visit — see error-pages notes.
 */

const CACHE_NAME = 'atmabiswas-offline-v1';
const OFFLINE_URL = '/offline.html';

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            return cache.add(OFFLINE_URL);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys.filter(function (key) { return key !== CACHE_NAME; })
                    .map(function (key) { return caches.delete(key); })
            );
        }).then(function () { return self.clients.claim(); })
    );
});

self.addEventListener('fetch', function (event) {
    // Only handle top-level page navigations; let every other request
    // (images, stylesheets, API calls) go straight to the network as normal.
    if (event.request.mode !== 'navigate') return;

    event.respondWith(
        fetch(event.request).catch(function () {
            return caches.match(OFFLINE_URL);
        })
    );
});
