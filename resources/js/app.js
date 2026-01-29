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

const GEO_CACHE_KEY = 'faltou:geocode-cache';
const DEFAULT_LOCALITY_LABEL = 'Localidade desconhecida';
const MAX_NOTE_LENGTH = 160;
const MAX_COMMENT_LENGTH = 140;
const API_BASE = '/api';

const reportKey = (type) => `faltou:reports:${type}`;
const commentKey = (type) => `faltou:comments:${type}`;
const pendingReportKey = (type) => `faltou:reports:pending:${type}`;
const pendingCommentKey = (type) => `faltou:comments:pending:${type}`;
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

const generateId = () => {
    if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
        return crypto.randomUUID();
    }
    return `id-${Date.now()}-${Math.random().toString(16).slice(2)}`;
};

const normalizeTimestamp = (value) => {
    if (typeof value === 'number') {
        return value;
    }
    if (!value) {
        return Date.now();
    }
    const parsed = Date.parse(value);
    return Number.isNaN(parsed) ? Date.now() : parsed;
};

const normalizeReport = (report) => {
    if (!report) {
        return null;
    }
    return {
        id: report.id ?? report.uuid ?? generateId(),
        type: report.type,
        lat: Number.parseFloat(report.lat),
        lng: Number.parseFloat(report.lng),
        locality: report.locality ?? DEFAULT_LOCALITY_LABEL,
        note: report.note ?? '',
        impact: report.impact ?? '',
        method: report.method ?? 'manual',
        createdAt: normalizeTimestamp(report.createdAt ?? report.created_at),
        pending: Boolean(report.pending),
    };
};

const normalizeComment = (comment) => {
    if (!comment) {
        return null;
    }
    return {
        id: comment.id ?? generateId(),
        text: comment.text ?? '',
        createdAt: normalizeTimestamp(comment.createdAt ?? comment.created_at),
        pending: Boolean(comment.pending),
    };
};

const mergeByCreatedAt = (items) =>
    items
        .filter(Boolean)
        .sort((a, b) => b.createdAt - a.createdAt)
        .filter(within24h);

const apiRequest = async (path, options = {}) => {
    const response = await fetch(`${API_BASE}${path}`, {
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            ...(options.headers ?? {}),
        },
        ...options,
    });

    if (!response.ok) {
        let message = `API ${response.status}`;
        try {
            const data = await response.json();
            if (data && data.message) {
                message = data.message;
            }
        } catch (e) {
        }
        const error = new Error(message);
        error.status = response.status;
        throw error;
    }

    return response.json();
};


const getStoredReports = (type) => {
    const cached = storage.read(reportKey(type)).map(normalizeReport);
    const pending = storage.read(pendingReportKey(type)).map(normalizeReport);
    return mergeByCreatedAt([...pending, ...cached]);
};

const getStoredComments = (type) => {
    const cached = storage.read(commentKey(type)).map(normalizeComment);
    const pending = storage.read(pendingCommentKey(type)).map(normalizeComment);
    return mergeByCreatedAt([...pending, ...cached]);
};

const loadReports = async (type) => {
    if (!navigator.onLine) {
        return getStoredReports(type);
    }
    try {
        const data = await apiRequest(`/reports?type=${type}`);
        const normalized = Array.isArray(data) ? data.map(normalizeReport).filter(Boolean) : [];
        const cleaned = mergeByCreatedAt(normalized);
        storage.write(reportKey(type), cleaned);
        return mergeByCreatedAt([...storage.read(pendingReportKey(type)).map(normalizeReport), ...cleaned]);
    } catch (error) {
        return getStoredReports(type);
    }
};

const loadComments = async (type) => {
    if (!navigator.onLine) {
        return getStoredComments(type);
    }
    try {
        const data = await apiRequest(`/comments?type=${type}`);
        const normalized = Array.isArray(data) ? data.map(normalizeComment).filter(Boolean) : [];
        const cleaned = mergeByCreatedAt(normalized);
        storage.write(commentKey(type), cleaned);
        return mergeByCreatedAt([
            ...storage.read(pendingCommentKey(type)).map(normalizeComment),
            ...cleaned,
        ]);
    } catch (error) {
        return getStoredComments(type);
    }
};

const savePendingReport = (type, payload) => {
    const pending = storage.read(pendingReportKey(type));
    pending.unshift({ ...payload, pending: true });
    storage.write(pendingReportKey(type), pending.filter(within24h));
};

const savePendingComment = (type, payload) => {
    const pending = storage.read(pendingCommentKey(type));
    pending.unshift({ ...payload, pending: true });
    storage.write(pendingCommentKey(type), pending.filter(within24h));
};

const syncPendingReports = async () => {
    if (!navigator.onLine) {
        return;
    }
    await Promise.all(
        Object.keys(OUTAGE_META).map(async (type) => {
            const pending = storage.read(pendingReportKey(type));
            if (!pending.length) {
                return;
            }
            const remaining = [];
            for (const report of pending) {
                try {
                    const response = await apiRequest('/reports', {
                        method: 'POST',
                        body: JSON.stringify({
                            type,
                            lat: report.lat,
                            lng: report.lng,
                            locality: report.locality,
                            note: report.note,
                            impact: report.impact,
                            method: report.method,
                        }),
                    });
                    const normalized = normalizeReport(response);
                    const cached = storage.read(reportKey(type)).map(normalizeReport);
                    storage.write(reportKey(type), mergeByCreatedAt([normalized, ...cached]));
                } catch (error) {
                    remaining.push(report);
                }
            }
            storage.write(pendingReportKey(type), remaining.filter(within24h));
        })
    );
};

const syncPendingComments = async () => {
    if (!navigator.onLine) {
        return;
    }
    await Promise.all(
        Object.keys(OUTAGE_META).map(async (type) => {
            const pending = storage.read(pendingCommentKey(type));
            if (!pending.length) {
                return;
            }
            const remaining = [];
            for (const comment of pending) {
                try {
                    const response = await apiRequest('/comments', {
                        method: 'POST',
                        body: JSON.stringify({
                            type,
                            text: comment.text,
                        }),
                    });
                    const normalized = normalizeComment(response);
                    const cached = storage.read(commentKey(type)).map(normalizeComment);
                    storage.write(commentKey(type), mergeByCreatedAt([normalized, ...cached]));
                } catch (error) {
                    remaining.push(comment);
                }
            }
            storage.write(pendingCommentKey(type), remaining.filter(within24h));
        })
    );
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

    const leftHeader = document.createElement('div');
    leftHeader.className = 'flex items-center gap-2';
    leftHeader.appendChild(label);

    if (item.pending) {
        const pendingBadge = document.createElement('span');
        pendingBadge.className = 'pill pill-sand !py-0.5 !text-[10px] !normal-case !tracking-normal';
        pendingBadge.textContent = 'A aguardar envio';
        leftHeader.appendChild(pendingBadge);
    }

    header.appendChild(leftHeader);
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

    const header = document.createElement('div');
    header.className = 'flex items-center justify-between mb-1';

    const time = document.createElement('div');
    time.className = 'text-xs text-ink/50';
    time.textContent = formatRelative(item.createdAt);

    header.appendChild(time);

    if (item.pending) {
        const pendingBadge = document.createElement('span');
        pendingBadge.className = 'pill pill-sand !py-0.5 !text-[10px] !normal-case !tracking-normal';
        pendingBadge.textContent = 'A aguardar envio';
        header.appendChild(pendingBadge);
    }

    const text = document.createElement('div');
    text.className = 'text-sm text-ink';
    text.textContent = item.text;

    card.appendChild(header);
    card.appendChild(text);

    return card;
};

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

const reportMapRegistry = new Map();

const createPopupContent = (report) => {
    const container = document.createElement('div');
    const title = document.createElement('div');
    title.className = 'font-semibold';
    title.textContent = OUTAGE_META[report.type]?.label ?? 'Aviso';

    const locality = document.createElement('div');
    locality.className = 'text-sm';
    locality.textContent = report.locality || DEFAULT_LOCALITY_LABEL;

    const time = document.createElement('div');
    time.className = 'text-xs text-ink/60';
    time.textContent = formatRelative(report.createdAt);

    container.appendChild(title);
    container.appendChild(locality);
    container.appendChild(time);

    if (report.note) {
        const note = document.createElement('div');
        note.className = 'text-xs text-ink/70';
        note.textContent = report.note;
        container.appendChild(note);
    }

    return container;
};

const initReportMaps = () => {
    const mapElements = document.querySelectorAll('[data-report-map]');
    if (!mapElements.length) {
        return;
    }

    const initialize = () => {
        mapElements.forEach((element) => {
            if (reportMapRegistry.has(element) || !window.L) {
                return;
            }
            element.textContent = '';
            const scope = element.dataset.mapScope || 'all';
            const map = window.L.map(element, { zoomControl: false, scrollWheelZoom: false }).setView(
                [DEFAULT_CENTER.lat, DEFAULT_CENTER.lng],
                6
            );
            window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 17,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            const layer = window.L.markerClusterGroup
                ? window.L.markerClusterGroup({ showCoverageOnHover: false, maxClusterRadius: 50 })
                : window.L.layerGroup();
            layer.addTo(map);
            reportMapRegistry.set(element, { map, layer, scope });
        });

        refreshReportMaps();
    };

    if (window.L) {
        initialize();
    } else {
        mapElements.forEach((element) => {
            element.textContent = 'Mapa indisponível no momento.';
        });
        window.addEventListener('load', initialize, { once: true });
    }
};

const refreshReportMaps = async () => {
    if (reportMapRegistry.size === 0) {
        return;
    }
    const powerReports = await hydrateReports('power', getStoredReports('power'));
    const waterReports = await hydrateReports('water', getStoredReports('water'));
    const allReports = [...powerReports, ...waterReports].filter(within24h);

    reportMapRegistry.forEach(({ map, layer, scope }) => {
        layer.clearLayers();
        let reports = allReports;
        if (scope === 'power') {
            reports = powerReports;
        } else if (scope === 'water') {
            reports = waterReports;
        }

        if (!reports.length) {
            map.setView([DEFAULT_CENTER.lat, DEFAULT_CENTER.lng], 6);
            return;
        }

        const bounds = [];
        reports.forEach((report) => {
            const color = report.type === 'water' ? '#4f7f89' : '#f06b36';
            const marker = window.L.circleMarker([report.lat, report.lng], {
                radius: 8,
                color,
                fillColor: color,
                fillOpacity: 0.9,
                weight: 2,
            });
            layer.addLayer(marker);
            marker.bindPopup(createPopupContent(report));
            bounds.push([report.lat, report.lng]);
        });

        if (bounds.length) {
            map.fitBounds(bounds, { padding: [32, 32], maxZoom: 12 });
        }
    });
};

const updateCounts = () => {
    document.querySelectorAll('[data-report-count]').forEach((element) => {
        const type = element.dataset.reportCount;
        const reports = type ? getStoredReports(type) : [];
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
    const powerReports = await hydrateReports('power', await loadReports('power'));
    const waterReports = await hydrateReports('water', await loadReports('water'));
    const reports = [...powerReports, ...waterReports]
        .filter(within24h)
        .sort((a, b) => b.createdAt - a.createdAt);

    renderList(container, reports, createReportCard, 'Ainda não existem avisos nas últimas 24 horas.');
    updateCounts();
    refreshReportMaps();
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
        const reports = await hydrateReports(type, await loadReports(type));
        renderList(reportList, reports, createReportCard, 'Ainda não existem avisos nesta página.');
        refreshReportMaps();
    };

    const refreshComments = async () => {
        const comments = await loadComments(type);
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
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.textContent : '';

            event.preventDefault();

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'A publicar...';
            }

            const lat = parseCoordinate(latInput?.value ?? '');
            const lng = parseCoordinate(lngInput?.value ?? '');
            if (lat === null || lng === null) {
                if (status) {
                    status.textContent = 'Defina uma localização antes de publicar o aviso.';
                    status.className = 'text-xs text-ember font-medium';
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
                return;
            }
            const noteValue = noteInput?.value.trim() ?? '';
            if (noteValue.length > MAX_NOTE_LENGTH) {
                if (status) {
                    status.textContent = `Descrição demasiado longa. Máximo ${MAX_NOTE_LENGTH} caracteres.`;
                    status.className = 'text-xs text-ember font-medium';
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
                return;
            }

            if (status) {
                status.textContent = 'A processar aviso...';
                status.className = 'text-xs text-ink/60';
            }

            let locality = currentLocality;
            if (!locality || locality === DEFAULT_LOCALITY_LABEL) {
                locality = await resolveLocality(lat, lng);
            }
            const payload = {
                type,
                lat,
                lng,
                method: locationMethod?.value ?? 'manual',
                note: noteValue,
                impact: impactInput?.value ?? 'na',
                locality,
                createdAt: Date.now(),
            };

            let savedRemotely = false;
            let blockedByServer = false;
            let retryCount = 0;
            const maxRetries = 1;

            while (navigator.onLine && !savedRemotely && !blockedByServer && retryCount <= maxRetries) {
                if (retryCount > 0 && status) {
                    status.textContent = `A tentar enviar novamente...`;
                }
                try {
                    const response = await apiRequest('/reports', {
                        method: 'POST',
                        body: JSON.stringify({
                            type: payload.type,
                            lat: payload.lat,
                            lng: payload.lng,
                            locality: payload.locality,
                            note: payload.note,
                            impact: payload.impact,
                            method: payload.method,
                        }),
                    });
                    const normalized = normalizeReport(response);
                    const cached = storage.read(reportKey(type)).map(normalizeReport);
                    storage.write(reportKey(type), mergeByCreatedAt([normalized, ...cached]));
                    savedRemotely = true;
                } catch (error) {
                    if (error && error.status === 429) {
                        blockedByServer = true;
                        break;
                    }
                    retryCount++;
                    if (retryCount <= maxRetries && navigator.onLine) {
                        await new Promise((resolve) => setTimeout(resolve, 1500));
                    }
                }
            }

            if (blockedByServer) {
                if (status) {
                    status.textContent = 'Limite de envios atingido. Tente novamente daqui a pouco.';
                    status.className = 'text-xs text-ember font-medium';
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
                return;
            }

            if (!savedRemotely) {
                savePendingReport(type, { id: generateId(), ...payload });
            }
            if (noteInput) noteInput.value = '';
            if (status) {
                if (savedRemotely) {
                    status.textContent = 'Aviso publicado com sucesso. Obrigado por ajudar.';
                    status.className = 'text-xs text-river font-medium';
                } else if (!navigator.onLine) {
                    status.textContent = 'Aviso guardado localmente. Será enviado quando houver ligação.';
                    status.className = 'text-xs text-ember font-medium';
                } else {
                    status.textContent = 'Ligação instável. O aviso foi guardado e será enviado em breve.';
                    status.className = 'text-xs text-ember font-medium';
                }
            }
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }
            await refreshReports();
            updateCounts();
            setupHomeFeed();
            refreshReportMaps();
        });
    }

    if (commentForm) {
        commentForm.addEventListener('submit', async (event) => {
            const submitBtn = commentForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.textContent : '';

            event.preventDefault();

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'A enviar...';
            }

            const commentText = page.querySelector('[data-comment-text]');
            const value = commentText?.value.trim() ?? '';
            if (!value) {
                if (commentStatus) {
                    commentStatus.textContent = 'Escreva um comentário antes de enviar.';
                    commentStatus.className = 'text-xs text-ember font-medium';
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
                return;
            }
            if (value.length > MAX_COMMENT_LENGTH) {
                if (commentStatus) {
                    commentStatus.textContent = `Comentário demasiado longo. Máximo ${MAX_COMMENT_LENGTH} caracteres.`;
                    commentStatus.className = 'text-xs text-ember font-medium';
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
                return;
            }

            if (commentStatus) {
                commentStatus.textContent = 'A processar comentário...';
                commentStatus.className = 'text-xs text-ink/60';
            }

            const payload = {
                id: generateId(),
                text: value,
                createdAt: Date.now(),
            };

            let savedRemotely = false;
            let blockedByServer = false;
            let retryCount = 0;
            const maxRetries = 1;

            while (navigator.onLine && !savedRemotely && !blockedByServer && retryCount <= maxRetries) {
                if (retryCount > 0 && commentStatus) {
                    commentStatus.textContent = 'A tentar enviar comentário novamente...';
                }
                try {
                    const response = await apiRequest('/comments', {
                        method: 'POST',
                        body: JSON.stringify({
                            type,
                            text: payload.text,
                        }),
                    });
                    const normalized = normalizeComment(response);
                    const cached = storage.read(commentKey(type)).map(normalizeComment);
                    storage.write(commentKey(type), mergeByCreatedAt([normalized, ...cached]));
                    savedRemotely = true;
                } catch (error) {
                    if (error && error.status === 429) {
                        blockedByServer = true;
                        break;
                    }
                    retryCount++;
                    if (retryCount <= maxRetries && navigator.onLine) {
                        await new Promise((resolve) => setTimeout(resolve, 1500));
                    }
                }
            }

            if (blockedByServer) {
                if (commentStatus) {
                    commentStatus.textContent = 'Limite de comentários atingido. Tente novamente daqui a pouco.';
                    commentStatus.className = 'text-xs text-ember font-medium';
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
                return;
            }

            if (!savedRemotely) {
                savePendingComment(type, payload);
            }
            if (commentText) commentText.value = '';
            if (commentStatus) {
                if (savedRemotely) {
                    commentStatus.textContent = 'Comentário enviado com sucesso.';
                    commentStatus.className = 'text-xs text-river font-medium';
                } else if (!navigator.onLine) {
                    commentStatus.textContent = 'Comentário guardado localmente. Será enviado quando houver ligação.';
                    commentStatus.className = 'text-xs text-ember font-medium';
                } else {
                    commentStatus.textContent = 'Ligação instável. O comentário foi guardado e será enviado em breve.';
                    commentStatus.className = 'text-xs text-ember font-medium';
                }
            }
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }
            await refreshComments();
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
initReportMaps();
setupHomeFeed();
setupOutagePage();
syncPendingReports();
syncPendingComments();
registerServiceWorker();

const handleOnline = () => {
    updateConnectionState();
    syncPendingReports();
    syncPendingComments();
    setupHomeFeed();
    refreshReportMaps();
};

window.addEventListener('online', handleOnline);
window.addEventListener('offline', updateConnectionState);
