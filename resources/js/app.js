import './bootstrap';

const ONE_DAY_MS = 24 * 60 * 60 * 1000;
const DEFAULT_CENTER = { lat: 39.5, lng: -8.0 };

const ICONS = {
    power: '<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>',
    water: '<path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/>',
    pin: '<path d="M20 10c0 4.99-5.54 10.19-7.4 11.8a1 1 0 0 1-1.2 0C9.54 20.19 4 14.99 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>',
    check: '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
    alert: '<circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>',
};

const icon = (name, size = 18) => {
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('width', size);
    svg.setAttribute('height', size);
    svg.setAttribute('viewBox', '0 0 24 24');
    svg.setAttribute('fill', 'none');
    svg.setAttribute('stroke', 'currentColor');
    svg.setAttribute('stroke-width', '2');
    svg.setAttribute('stroke-linecap', 'round');
    svg.setAttribute('stroke-linejoin', 'round');
    svg.setAttribute('aria-hidden', 'true');
    svg.innerHTML = ICONS[name] ?? '';
    return svg;
};

const OUTAGE_META = {
    power: {
        label: 'Eletricidade',
        pill: 'pill-ember',
        color: '#e8590c',
    },
    water: {
        label: 'Água',
        pill: 'pill-river',
        color: '#0b7285',
    },
};

const IMPACT_LABELS = {
    residencial: 'Residencial',
    comercial: 'Comercial',
    rua: 'Via pública',
    outros: 'Outros',
};

const track = (event, properties = {}) => {
    try {
        if (typeof window.op === 'function') {
            window.op('track', event, properties);
        }
    } catch (error) {}
};

const setStatus = (element, text, tone = '') => {
    if (!element) {
        return;
    }
    element.textContent = text;
    element.classList.remove('is-error', 'is-success', 'is-warning');
    if (tone) {
        element.classList.add(`is-${tone}`);
    }
};

const showToast = (text, tone = 'success') => {
    const region = document.querySelector('[data-toast-region]');
    if (!region) {
        return;
    }
    const toast = document.createElement('div');
    toast.className = 'toast';
    const glyph = icon(tone === 'success' ? 'check' : 'alert', 18);
    glyph.classList.add('mt-0.5', 'shrink-0', tone === 'success' ? 'text-emerald-300' : 'text-amber-300');
    const body = document.createElement('span');
    body.textContent = text;
    toast.append(glyph, body);
    region.appendChild(toast);
    window.setTimeout(() => {
        toast.style.transition = 'opacity .3s';
        toast.style.opacity = '0';
        window.setTimeout(() => toast.remove(), 300);
    }, 4000);
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

const removePendingReport = (type, id) => {
    const pending = storage.read(pendingReportKey(type));
    const filtered = pending.filter((r) => (r.id ?? r.uuid) !== id);
    storage.write(pendingReportKey(type), filtered);
};

const savePendingComment = (type, payload) => {
    const pending = storage.read(pendingCommentKey(type));
    pending.unshift({ ...payload, pending: true });
    storage.write(pendingCommentKey(type), pending.filter(within24h));
};

const removePendingComment = (type, id) => {
    const pending = storage.read(pendingCommentKey(type));
    const filtered = pending.filter((c) => c.id !== id);
    storage.write(pendingCommentKey(type), filtered);
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
        const emptyIcon = icon('pin', 20);
        emptyIcon.classList.add('mb-1', 'text-muted/60');
        const emptyText = document.createElement('span');
        emptyText.textContent = emptyLabel;
        empty.append(emptyIcon, emptyText);
        container.appendChild(empty);
        return;
    }
    items.forEach((item) => container.appendChild(renderItem(item)));
};

const createPendingBadge = () => {
    const badge = document.createElement('span');
    badge.className = 'pill pill-sand !px-2 !py-0.5 !text-[11px]';
    badge.textContent = 'A aguardar envio';
    return badge;
};

const createReportCard = (item) => {
    const meta = OUTAGE_META[item.type] || { label: 'Aviso', pill: 'pill' };
    const card = document.createElement('article');
    card.className = 'report-item';

    const iconWrap = document.createElement('span');
    iconWrap.className = `report-icon report-icon-${item.type === 'water' ? 'water' : 'power'}`;
    iconWrap.appendChild(icon(item.type === 'water' ? 'water' : 'power', 18));

    const body = document.createElement('div');
    body.className = 'min-w-0 flex-1';

    const header = document.createElement('div');
    header.className = 'flex items-start justify-between gap-2';

    const locality = document.createElement('div');
    locality.className = 'truncate text-sm font-semibold text-ink';
    locality.textContent = item.locality || DEFAULT_LOCALITY_LABEL;

    const time = document.createElement('time');
    time.className = 'shrink-0 text-xs text-muted';
    time.dateTime = new Date(item.createdAt).toISOString();
    time.textContent = formatRelative(item.createdAt);

    header.append(locality, time);

    const metaRow = document.createElement('div');
    metaRow.className = 'mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-muted';
    const typeLabel = document.createElement('span');
    typeLabel.textContent = meta.label;
    metaRow.appendChild(typeLabel);
    if (item.impact) {
        const sep = document.createElement('span');
        sep.textContent = '·';
        const impact = document.createElement('span');
        impact.textContent = IMPACT_LABELS[item.impact] ?? item.impact;
        metaRow.append(sep, impact);
    }
    if (item.pending) {
        metaRow.appendChild(createPendingBadge());
    }

    body.append(header, metaRow);

    if (item.note) {
        const note = document.createElement('p');
        note.className = 'mt-1.5 text-sm break-words text-ink/80';
        note.textContent = item.note;
        body.appendChild(note);
    }

    card.append(iconWrap, body);
    return card;
};

const createCommentCard = (item) => {
    const card = document.createElement('article');
    card.className = 'comment-item';

    const header = document.createElement('div');
    header.className = 'mb-1 flex items-center gap-2';

    const time = document.createElement('time');
    time.className = 'text-xs text-muted';
    time.dateTime = new Date(item.createdAt).toISOString();
    time.textContent = formatRelative(item.createdAt);

    header.appendChild(time);

    if (item.pending) {
        header.appendChild(createPendingBadge());
    }

    const text = document.createElement('p');
    text.className = 'text-sm break-words text-ink';
    text.textContent = item.text;

    card.append(header, text);
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
    container.className = 'min-w-[10rem] max-w-[15rem]';

    const title = document.createElement('div');
    title.className = 'flex items-center gap-1.5 text-xs font-semibold';
    title.style.color = OUTAGE_META[report.type]?.color ?? 'inherit';
    title.append(icon(report.type === 'water' ? 'water' : 'power', 12));
    const titleText = document.createElement('span');
    titleText.textContent = `${OUTAGE_META[report.type]?.label ?? 'Aviso'} · ${formatRelative(report.createdAt)}`;
    title.appendChild(titleText);

    const locality = document.createElement('div');
    locality.className = 'mt-1 text-sm font-semibold text-ink';
    locality.textContent = report.locality || DEFAULT_LOCALITY_LABEL;

    container.append(title, locality);

    if (report.note) {
        const note = document.createElement('div');
        note.className = 'mt-1 text-xs text-muted';
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
            element.textContent = 'A carregar mapa...';
        });
        window.addEventListener('load', initialize, { once: true });
    }
};

const refreshReportMaps = async () => {
    if (reportMapRegistry.size === 0) {
        return;
    }

    if (window._mapRefreshing) {
        window._mapNeedsRefresh = true;
        return;
    }

    window._mapRefreshing = true;
    window._mapNeedsRefresh = false;

    try {
        const powerReports = await hydrateReports('power', getStoredReports('power'));
        const waterReports = await hydrateReports('water', getStoredReports('water'));
        const allReports = [...powerReports, ...waterReports];

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
            const markers = [];

            reports.forEach((report) => {
                if (typeof report.lat !== 'number' || typeof report.lng !== 'number' ||
                    isNaN(report.lat) || isNaN(report.lng)) {
                    return;
                }

                const color = OUTAGE_META[report.type]?.color ?? '#15171c';
                const marker = window.L.circleMarker([report.lat, report.lng], {
                    radius: 8,
                    color: '#ffffff',
                    fillColor: color,
                    fillOpacity: 1,
                    weight: 2.5,
                    className: 'map-marker-vibrant'
                });

                marker.bindPopup(createPopupContent(report));
                markers.push(marker);
                bounds.push([report.lat, report.lng]);
            });

            if (markers.length > 0) {
                if (layer.addLayers) {
                    layer.addLayers(markers);
                } else {
                    markers.forEach(m => layer.addLayer(m));
                }
            }

            if (bounds.length) {
                map.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
            }
        });
    } catch (error) {
        console.error('Error refreshing maps:', error);
    } finally {
        window._mapRefreshing = false;
        if (window._mapNeedsRefresh) {
            refreshReportMaps();
        }
    }
};

const updateCounts = () => {
    document.querySelectorAll('[data-report-count]').forEach((element) => {
        const type = element.dataset.reportCount;
        const types = type === 'all' ? Object.keys(OUTAGE_META) : [type].filter((t) => OUTAGE_META[t]);
        const total = types.reduce((sum, t) => sum + getStoredReports(t).length, 0);
        element.textContent = total.toString();
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

let homeFeedReports = [];
let homeFeedFilter = 'all';

const renderHomeFeed = () => {
    const container = document.querySelector('[data-report-feed]');
    if (!container) {
        return;
    }
    const reports =
        homeFeedFilter === 'all' ? homeFeedReports : homeFeedReports.filter((r) => r.type === homeFeedFilter);
    renderList(container, reports, createReportCard, 'Sem avisos nas últimas 24 horas.');
};

const setupFeedFilters = () => {
    const buttons = document.querySelectorAll('[data-feed-filter]');
    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            homeFeedFilter = button.dataset.feedFilter;
            buttons.forEach((b) => b.setAttribute('aria-selected', b === button ? 'true' : 'false'));
            renderHomeFeed();
        });
    });
};

const setupHomeFeed = async () => {
    const container = document.querySelector('[data-report-feed]');
    if (!container) {
        return;
    }
    const [powerReports, waterReports] = await Promise.all([loadReports('power'), loadReports('water')]);

    // Hydrate all at once before rendering
    const [hPower, hWater] = await Promise.all([
        hydrateReports('power', powerReports),
        hydrateReports('water', waterReports)
    ]);

    homeFeedReports = [...hPower, ...hWater]
        .filter(within24h)
        .sort((a, b) => b.createdAt - a.createdAt);

    renderHomeFeed();
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
    const getImpact = () =>
        page.querySelector('[data-impact]:checked')?.value ?? page.querySelector('select[data-impact]')?.value ?? '';
    const locationStatusBox = page.querySelector('[data-location-status-box]');
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
            locationStatus.textContent = currentLocality;
        }
    };

    const setLocation = (lat, lng, method) => {
        if (latInput) latInput.value = lat.toFixed(5);
        if (lngInput) lngInput.value = lng.toFixed(5);
        if (locationMethod) locationMethod.value = method;
        if (locationStatus) {
            locationStatus.textContent = 'Localização definida. A obter localidade...';
        }
        locationStatusBox?.classList.add('is-set');
        updateLocality(lat, lng);
        if (mapInstance) {
            const position = [lat, lng];
            if (!marker) {
                marker = window.L.circleMarker(position, {
                    radius: 11,
                    color: '#ffffff',
                    weight: 3,
                    fillColor: OUTAGE_META[type]?.color ?? '#15171c',
                    fillOpacity: 1,
                    className: 'map-marker-selected',
                }).addTo(mapInstance);
            } else {
                marker.setLatLng(position);
            }
            mapInstance.setView(position, Math.max(mapInstance.getZoom(), 14));
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
            mapElement.textContent = 'A carregar mapa...';
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
                    track('location_gps', { type, success: true });
                },
                () => {
                    track('location_gps', { type, success: false });
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
                    setStatus(status, 'Defina uma localização antes de publicar o aviso.', 'error');
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
                    setStatus(status, `Descrição demasiado longa. Máximo ${MAX_NOTE_LENGTH} caracteres.`, 'error');
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
                return;
            }

            if (status) {
                setStatus(status, 'A processar aviso...');
            }

            let locality = currentLocality;
            if (!locality || locality === DEFAULT_LOCALITY_LABEL) {
                locality = await resolveLocality(lat, lng);
            }
            const payload = {
                id: generateId(),
                type,
                lat,
                lng,
                method: locationMethod?.value ?? 'manual',
                note: noteValue,
                impact: getImpact() || 'outros',
                locality,
                createdAt: Date.now(),
            };

            // Optimistic update
            savePendingReport(type, payload);
            if (noteInput) {
                noteInput.value = '';
                noteInput.dispatchEvent(new Event('input'));
            }
            await refreshReports();
            updateCounts();
            setupHomeFeed();

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
                    removePendingReport(type, payload.id);
                    track('report_created', {
                        type,
                        impact: payload.impact,
                        method: payload.method,
                        has_note: Boolean(payload.note),
                    });
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
                    setStatus(status, 'Limite de envios atingido. Tente novamente daqui a pouco.', 'error');
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
                return;
            }

            if (status) {
                if (savedRemotely) {
                    setStatus(status, 'Aviso publicado com sucesso. Obrigado por ajudar.', 'success');
                    showToast('Aviso publicado. Obrigado por ajudar a comunidade.');
                } else if (!navigator.onLine) {
                    setStatus(status, 'Aviso guardado localmente. Será enviado quando houver ligação.', 'warning');
                } else {
                    setStatus(status, 'Ligação instável. O aviso foi guardado e será enviado em breve.', 'warning');
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
                    setStatus(commentStatus, 'Escreva um comentário antes de enviar.', 'error');
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
                return;
            }
            if (value.length > MAX_COMMENT_LENGTH) {
                if (commentStatus) {
                    setStatus(commentStatus, `Comentário demasiado longo. Máximo ${MAX_COMMENT_LENGTH} caracteres.`, 'error');
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
                return;
            }

            if (commentStatus) {
                setStatus(commentStatus, 'A processar comentário...');
            }

            const payload = {
                id: generateId(),
                text: value,
                createdAt: Date.now(),
            };

            // Optimistic update
            savePendingComment(type, payload);
            if (commentText) {
                commentText.value = '';
                commentText.dispatchEvent(new Event('input'));
            }
            await refreshComments();

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
                    removePendingComment(type, payload.id);
                    track('comment_created', { type });
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
                    setStatus(commentStatus, 'Limite de comentários atingido. Tente novamente daqui a pouco.', 'error');
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
                return;
            }

            if (commentStatus) {
                if (savedRemotely) {
                    setStatus(commentStatus, 'Comentário enviado com sucesso.', 'success');
                } else if (!navigator.onLine) {
                    setStatus(commentStatus, 'Comentário guardado localmente. Será enviado quando houver ligação.', 'warning');
                } else {
                    setStatus(commentStatus, 'Ligação instável. O comentário foi guardado e será enviado em breve.', 'warning');
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

const setupCharCounters = () => {
    document.querySelectorAll('[data-counted]').forEach((field) => {
        const counter = document.querySelector(`[data-count-for="${field.dataset.counted}"]`);
        if (!counter) {
            return;
        }
        const update = () => {
            counter.textContent = field.value.length.toString();
        };
        field.addEventListener('input', update);
        update();
    });
};

const CHECKLIST_KEY = 'faltou:kit-checklist';

const setupChecklist = () => {
    const root = document.querySelector('[data-checklist]');
    if (!root) {
        return;
    }
    const items = [...root.querySelectorAll('[data-checklist-item]')];
    const count = root.querySelector('[data-checklist-count]');
    const bar = root.querySelector('[data-checklist-bar]');
    const saved = storage.read(CHECKLIST_KEY);

    const update = () => {
        const checked = items.filter((item) => item.checked).map((item) => item.dataset.checklistItem);
        if (count) count.textContent = checked.length.toString();
        if (bar) bar.style.width = `${(checked.length / Math.max(items.length, 1)) * 100}%`;
        storage.write(CHECKLIST_KEY, checked);
    };

    items.forEach((item) => {
        item.checked = saved.includes(item.dataset.checklistItem);
        item.addEventListener('change', update);
    });
    update();
};

const setupDropdowns = () => {
    const dropdowns = document.querySelectorAll('[data-dropdown]');
    document.addEventListener('click', (event) => {
        dropdowns.forEach((dropdown) => {
            if (dropdown.open && !dropdown.contains(event.target)) {
                dropdown.open = false;
            }
        });
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            dropdowns.forEach((dropdown) => {
                dropdown.open = false;
            });
        }
    });
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
setupDropdowns();
setupCharCounters();
setupChecklist();
setupFeedFilters();
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
