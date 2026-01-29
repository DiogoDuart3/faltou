import './bootstrap';

const ONE_DAY_MS = 24 * 60 * 60 * 1000;
const DEFAULT_CENTER = { lat: 39.5, lng: -8.0 };

const OUTAGE_META = {
    power: {
        label: 'Eletricidade',
        pill: 'pill-ember',
    },
    water: {
        label: 'Água',
        pill: 'pill-river',
    },
};

const RATE_LIMITS = {
    report: { windowMs: 5 * 60 * 1000, max: 1 },
    comment: { windowMs: 3 * 60 * 1000, max: 3 },
};

const GEO_CACHE_KEY = 'faltou:geocode-cache';
const DEFAULT_LOCALITY_LABEL = 'Localidade desconhecida';
const MAX_NOTE_LENGTH = 160;
const MAX_COMMENT_LENGTH = 140;

const storage = {
    read(key) {
        try {
            const raw = window.localStorage.getItem(key);
            if (!raw) {
                return [];
            }
            const parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            return [];
        }
    },
    write(key, value) {
        try {
            window.localStorage.setItem(key, JSON.stringify(value));
        } catch (error) {}
    },
    readObject(key) {
        try {
            const raw = window.localStorage.getItem(key);
            if (!raw) {
                return {};
            }
            const parsed = JSON.parse(raw);
            return parsed && typeof parsed === 'object' && !Array.isArray(parsed) ? parsed : {};
        } catch (error) {
            return {};
        }
    },
    writeObject(key, value) {
        try {
            window.localStorage.setItem(key, JSON.stringify(value));
        } catch (error) {}
    },
};

const within24h = (item) => Date.now() - item.createdAt < ONE_DAY_MS;

const pruneItems = (key) => {
    const items = storage.read(key).filter(within24h);
    storage.write(key, items);
    return items;
};

const generateId = () => {
    if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
        return crypto.randomUUID();
    }
    return `id-${Date.now()}-${Math.random().toString(16).slice(2)}`;
};

const rateKey = (action, type) => `faltou:rate:${action}:${type}`;

const formatWait = (waitMs) => {
    const seconds = Math.ceil(waitMs / 1000);
    if (seconds < 60) {
        return `${seconds}s`;
    }
    const minutes = Math.ceil(seconds / 60);
    return `${minutes} min`;
};

const checkRateLimit = (action, type) => {
    const limit = RATE_LIMITS[action];
    if (!limit) {
        return { allowed: true };
    }
    const now = Date.now();
    const recent = storage.read(rateKey(action, type)).filter((timestamp) => now - timestamp < limit.windowMs);
    if (recent.length >= limit.max) {
        const waitMs = Math.max(limit.windowMs - (now - recent[0]), 0);
        return { allowed: false, waitMs };
    }
    recent.push(now);
    storage.write(rateKey(action, type), recent);
    return { allowed: true };
};

const geoKey = (lat, lng) => `${lat.toFixed(3)},${lng.toFixed(3)}`;

const getCachedLocality = (lat, lng) => {
    const cache = storage.readObject(GEO_CACHE_KEY);
    return cache[geoKey(lat, lng)];
};

const setCachedLocality = (lat, lng, locality) => {
    const cache = storage.readObject(GEO_CACHE_KEY);
    cache[geoKey(lat, lng)] = locality;
    storage.writeObject(GEO_CACHE_KEY, cache);
};

const resolveLocality = async (lat, lng) => {
    const cached = getCachedLocality(lat, lng);
    if (cached) {
        return cached;
    }
    if (!navigator.onLine) {
        return DEFAULT_LOCALITY_LABEL;
    }
    const controller = new AbortController();
    const timeout = window.setTimeout(() => controller.abort(), 4000);
    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&zoom=14&addressdetails=1`;
    try {
        const response = await fetch(url, {
            signal: controller.signal,
            headers: { 'Accept-Language': 'pt-PT' },
        });
        if (!response.ok) {
            return DEFAULT_LOCALITY_LABEL;
        }
        const data = await response.json();
        const address = data.address || {};
        const locality =
            address.city ||
            address.town ||
            address.village ||
            address.suburb ||
            address.municipality ||
            address.county ||
            address.state ||
            (data.display_name ? data.display_name.split(',')[0] : '');
        const safeLocality = locality ? locality.trim() : DEFAULT_LOCALITY_LABEL;
        setCachedLocality(lat, lng, safeLocality);
        return safeLocality;
    } catch (error) {
        return DEFAULT_LOCALITY_LABEL;
    } finally {
        window.clearTimeout(timeout);
    }
};

const reportKey = (type) => `faltou:reports:${type}`;
const commentKey = (type) => `faltou:comments:${type}`;

const formatRelative = (timestamp) => {
    const diff = Math.max(Date.now() - timestamp, 0);
    const minutes = Math.round(diff / 60000);
    if (minutes < 1) {
        return 'agora mesmo';
    }
    if (minutes < 60) {
        return `há ${minutes} min`;
    }
    const hours = Math.floor(minutes / 60);
    if (hours < 24) {
        return `há ${hours}h`;
    }
    const days = Math.floor(hours / 24);
    return `há ${days}d`;
};

const renderList = (container, items, renderItem, emptyLabel) => {
    if (!container) {
        return;
    }
    container.innerHTML = '';
    if (items.length === 0) {
        const empty = document.createElement('div');
        empty.className = 'empty-state';
        empty.textContent = emptyLabel;
        container.appendChild(empty);
        return;
    }
    items.forEach((item) => container.appendChild(renderItem(item)));
};

const createReportCard = (item) => {
    const meta = OUTAGE_META[item.type] || { label: 'Aviso', pill: 'pill' };
    const card = document.createElement('div');
    card.className = 'rounded-2xl border border-ink/10 bg-white/80 p-4 text-sm text-ink/70';

    const header = document.createElement('div');
    header.className = 'flex items-center justify-between';

    const label = document.createElement('span');
    label.className = `pill ${meta.pill}`;
    label.textContent = meta.label;

    const time = document.createElement('span');
    time.className = 'text-xs text-ink/50';
    time.textContent = formatRelative(item.createdAt);

    header.appendChild(label);
    header.appendChild(time);

    const impact = document.createElement('div');
    impact.className = 'mt-3 text-xs uppercase tracking-[0.2em] text-ink/50';
    impact.textContent = item.impact || 'impacto não indicado';

    const locality = document.createElement('div');
    locality.className = 'mt-2 text-sm font-medium text-ink';
    locality.textContent = `Localidade: ${item.locality || DEFAULT_LOCALITY_LABEL}`;

    const note = document.createElement('div');
    note.className = 'mt-2 text-sm text-ink/70';
    note.textContent = item.note ? item.note : 'Sem descrição adicional.';

    card.appendChild(header);
    card.appendChild(impact);
    card.appendChild(locality);
    card.appendChild(note);

    return card;
};

const createCommentCard = (item) => {
    const card = document.createElement('div');
    card.className = 'rounded-2xl border border-ink/10 bg-white/80 p-3 text-sm text-ink/70';

    const time = document.createElement('div');
    time.className = 'text-xs text-ink/50';
    time.textContent = formatRelative(item.createdAt);

    const text = document.createElement('div');
    text.className = 'mt-1 text-sm text-ink';
    text.textContent = item.text;

    card.appendChild(time);
    card.appendChild(text);

    return card;
};

const readReports = (type) => pruneItems(reportKey(type));
const readComments = (type) => pruneItems(commentKey(type));

const hydrateReports = async (type, reports) => {
    let changed = false;
    const hydrated = await Promise.all(
        reports.map(async (report) => {
            if (report.locality) {
                return report;
            }
            const locality = await resolveLocality(report.lat, report.lng);
            changed = true;
            return { ...report, locality };
        })
    );
    if (changed) {
        storage.write(reportKey(type), hydrated.filter(within24h));
    }
    return hydrated;
};

const updateCounts = () => {
    document.querySelectorAll('[data-report-count]').forEach((element) => {
        const type = element.dataset.reportCount;
        const reports = type ? readReports(type) : [];
        element.textContent = reports.length.toString();
    });
};

const updateConnectionState = () => {
    const text = document.querySelector('[data-connection-text]');
    if (!text) {
        return;
    }
    const online = navigator.onLine ? 'online' : 'offline';
    const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (connection && connection.saveData) {
        text.textContent = `Ligação ${online} (modo económico ativo)`;
        return;
    }
    if (connection && connection.effectiveType) {
        text.textContent = `Ligação ${online} (${connection.effectiveType})`;
        return;
    }
    text.textContent = `Ligação ${online}`;
};

const setupHomeFeed = async () => {
    const container = document.querySelector('[data-report-feed]');
    if (!container) {
        return;
    }
    const powerReports = await hydrateReports('power', readReports('power'));
    const waterReports = await hydrateReports('water', readReports('water'));
    const reports = [...powerReports, ...waterReports]
        .filter(within24h)
        .sort((a, b) => b.createdAt - a.createdAt);

    renderList(container, reports, createReportCard, 'Ainda não existem avisos nas últimas 24 horas.');
};

const setupOutagePage = () => {
    const page = document.querySelector('[data-outage-page]');
    if (!page) {
        return;
    }
    const type = page.dataset.outageType;
    const reportList = page.querySelector('[data-report-list]');
    const form = page.querySelector('[data-report-form]');
    const status = page.querySelector('[data-form-status]');
    const commentForm = page.querySelector('[data-comment-form]');
    const commentList = page.querySelector('[data-comment-list]');
    const commentStatus = page.querySelector('[data-comment-status]');
    const latInput = page.querySelector('[data-lat]');
    const lngInput = page.querySelector('[data-lng]');
    const noteInput = page.querySelector('[data-note]');
    const impactInput = page.querySelector('[data-impact]');
    const locationMethod = page.querySelector('[data-location-method]');
    const locationStatus = page.querySelector('[data-location-status]');
    const useLocationButton = page.querySelector('[data-use-location]');
    const mapElement = page.querySelector('[data-map]');

    let mapInstance = null;
    let marker = null;
    let currentLocality = DEFAULT_LOCALITY_LABEL;

    const updateLocality = async (lat, lng) => {
        currentLocality = await resolveLocality(lat, lng);
        if (locationStatus) {
            locationStatus.textContent = `Localidade: ${currentLocality}.`;
        }
    };

    const setLocation = (lat, lng, method) => {
        if (latInput) latInput.value = lat.toFixed(5);
        if (lngInput) lngInput.value = lng.toFixed(5);
        if (locationMethod) locationMethod.value = method;
        if (locationStatus) {
            locationStatus.textContent = `Localização definida (${method}). A obter localidade...`;
        }
        updateLocality(lat, lng);
        if (mapInstance) {
            const position = [lat, lng];
            if (!marker) {
                marker = window.L.marker(position).addTo(mapInstance);
            } else {
                marker.setLatLng(position);
            }
            mapInstance.setView(position, Math.max(mapInstance.getZoom(), 12));
        }
    };

    const initMap = () => {
        if (!mapElement || !window.L) {
            return;
        }
        mapElement.textContent = '';
        mapInstance = window.L.map(mapElement, { zoomControl: false }).setView(
            [DEFAULT_CENTER.lat, DEFAULT_CENTER.lng],
            6
        );
        window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 17,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(mapInstance);

        mapInstance.on('click', (event) => {
            setLocation(event.latlng.lat, event.latlng.lng, 'map');
        });
    };

    if (mapElement) {
        if (window.L) {
            initMap();
        } else {
            mapElement.textContent = 'Mapa indisponível. Use as coordenadas manuais.';
            window.addEventListener('load', initMap, { once: true });
        }
    }

    const refreshReports = async () => {
        const reports = await hydrateReports(type, readReports(type));
        renderList(reportList, reports, createReportCard, 'Ainda não existem avisos nesta página.');
    };

    const refreshComments = () => {
        const comments = readComments(type);
        renderList(commentList, comments, createCommentCard, 'Sem comentários ainda.');
    };

    const parseCoordinate = (value) => {
        const parsed = Number.parseFloat(value);
        return Number.isFinite(parsed) ? parsed : null;
    };

    if (latInput && lngInput) {
        const handleManual = () => {
            const lat = parseCoordinate(latInput.value);
            const lng = parseCoordinate(lngInput.value);
            if (lat === null || lng === null) {
                return;
            }
            setLocation(lat, lng, 'manual');
        };
        latInput.addEventListener('change', handleManual);
        lngInput.addEventListener('change', handleManual);
    }

    if (useLocationButton) {
        useLocationButton.addEventListener('click', () => {
            if (!navigator.geolocation) {
                if (locationStatus) {
                    locationStatus.textContent = 'Geolocalização indisponível neste dispositivo.';
                }
                return;
            }
            if (locationStatus) {
                locationStatus.textContent = 'A obter localização...';
            }
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    setLocation(position.coords.latitude, position.coords.longitude, 'gps');
                },
                () => {
                    if (locationStatus) {
                        locationStatus.textContent = 'Falha ao obter localização. Use o mapa manualmente.';
                    }
                },
                { enableHighAccuracy: false, timeout: 8000, maximumAge: 600000 }
            );
        });
    }

    if (form) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const lat = parseCoordinate(latInput?.value ?? '');
            const lng = parseCoordinate(lngInput?.value ?? '');
            if (lat === null || lng === null) {
                if (status) {
                    status.textContent = 'Defina uma localização antes de publicar o aviso.';
                }
                return;
            }
            const rate = checkRateLimit('report', type);
            if (!rate.allowed) {
                if (status) {
                    status.textContent = `Aguarde ${formatWait(rate.waitMs)} antes de enviar outro aviso.`;
                }
                return;
            }
            const noteValue = noteInput?.value.trim() ?? '';
            if (noteValue.length > MAX_NOTE_LENGTH) {
                if (status) {
                    status.textContent = `Descrição demasiado longa. Máximo ${MAX_NOTE_LENGTH} caracteres.`;
                }
                return;
            }
            let locality = currentLocality;
            if (!locality || locality === DEFAULT_LOCALITY_LABEL) {
                locality = await resolveLocality(lat, lng);
            }
            const reports = readReports(type);
            reports.unshift({
                id: generateId(),
                type,
                createdAt: Date.now(),
                lat,
                lng,
                method: locationMethod?.value ?? 'manual',
                note: noteValue,
                impact: impactInput?.value ?? 'na',
                locality,
            });
            storage.write(reportKey(type), reports.filter(within24h));
            if (noteInput) noteInput.value = '';
            if (status) {
                status.textContent = 'Aviso publicado. Obrigado por ajudar.';
            }
            await refreshReports();
            updateCounts();
            setupHomeFeed();
        });
    }

    if (commentForm) {
        commentForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const commentText = page.querySelector('[data-comment-text]');
            const value = commentText?.value.trim() ?? '';
            if (!value) {
                if (commentStatus) {
                    commentStatus.textContent = 'Escreva um comentário antes de enviar.';
                }
                return;
            }
            if (value.length > MAX_COMMENT_LENGTH) {
                if (commentStatus) {
                    commentStatus.textContent = `Comentário demasiado longo. Máximo ${MAX_COMMENT_LENGTH} caracteres.`;
                }
                return;
            }
            const rate = checkRateLimit('comment', type);
            if (!rate.allowed) {
                if (commentStatus) {
                    commentStatus.textContent = `Aguarde ${formatWait(rate.waitMs)} antes de comentar novamente.`;
                }
                return;
            }
            const comments = readComments(type);
            comments.unshift({
                id: generateId(),
                createdAt: Date.now(),
                text: value,
            });
            storage.write(commentKey(type), comments.filter(within24h));
            if (commentText) commentText.value = '';
            if (commentStatus) {
                commentStatus.textContent = 'Comentário enviado.';
            }
            refreshComments();
        });
    }

    refreshReports();
    refreshComments();
};

const registerServiceWorker = () => {
    if (!('serviceWorker' in navigator)) {
        return;
    }
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => undefined);
    });
};

updateCounts();
updateConnectionState();
setupHomeFeed();
setupOutagePage();
registerServiceWorker();

window.addEventListener('online', updateConnectionState);
window.addEventListener('offline', updateConnectionState);
