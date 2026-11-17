# DESIGN.md · Faltou

> If this file and the code disagree, the code wins until the person decides. Note the conflict under Open questions.

## Product
Plataforma comunitária para reportar e acompanhar falhas de eletricidade e água em Portugal. Usada sobretudo no
telemóvel, muitas vezes com rede fraca e em stress (casa às escuras, sem água); também em computador.
Páginas: mapa ao vivo (início), reportar luz, reportar água, 4 guias, contactos.
Trabalho principal: perceber em segundos "é só comigo?" e publicar um aviso em menos de um minuto, sem registo.

## Audience and voice
Público geral, todas as idades, pouco técnico. Português de Portugal, tratamento por "você", frases curtas e
diretas, sem jargão. Horas 14h30, datas 17/11, telefones "800 506 506".

## Mood
Words: utilitário, urgente, claro. Never like: landing page de SaaS, dashboard decorativo, app brincalhona com
mascotes, qualquer coisa com publicidade ou ruído visual (assumed).

## Brand
Design system source: `resources/css/app.css` (tokens `@theme` + classes de componentes) e
`resources/views/components/icon.blade.php` (ícones outline estilo Lucide).
Logo: `public/images/icon.png`. Neutros: porcelain `#f6f6f3`, stone, ink `#15171c`, muted, line.
Acentos com significado fixo: ember (laranja) = eletricidade, river (azul-petróleo) = água, sand = aviso,
danger = emergência/112, success = resolvido/online. Fonte: Inter.

## References
(assumidas — a pessoa disse "decide tu")
- Waze / Citymapper — take: mapa como protagonista, uma ação grande de reportar sempre ao alcance do polegar; avoid: tom brincalhão, mascotes.
- Downdetector — take: resposta imediata "há falhas?", contagens visíveis; avoid: publicidade e ruído.
- GOV.UK — take: texto grande, alto contraste, formulário passo a passo sem distrações; avoid: aspeto burocrático sem marca.

## Constraints
Telemóvel primeiro (360–430 px) e computador (1280 px+); tem de funcionar bem em ambos. WCAG AA, texto base
≥16 px, alvos de toque ≥44 px, inputs ≥16 px (sem zoom no iOS). Safe areas (notch / barra inferior).
Só tema claro. Um conjunto de ícones outline. Movimento mínimo, respeita `prefers-reduced-motion`.
Leve: sem JS/CSS de bibliotecas novas, sem imagens decorativas (redes fracas).

## Decisions log
- 2026-11-17 — Direção "utilitário e urgente" — escolhida pela pessoa.
- 2026-11-17 — Manter tokens, cores e Inter existentes; estender em vez de renomear — já são decisões tomadas no código.
- 2026-11-17 — Importação do design system para ferramenta de design não aplicável — não há ferramenta de design; o código é a fonte.
- 2026-11-17 — Telemóvel/tablet: barra inferior (Mapa · Falta luz · Falta água · Ajuda) substitui o menu hambúrguer — as duas ações principais ficam sempre ao alcance do polegar.
- 2026-11-17 — Início: mapa e avisos recentes no topo; hero de marketing e cartão "Estado do serviço" removidos — o trabalho da página é responder "é só comigo?".
- 2026-11-17 — Reportar: um só mapa (marcar local + ver avisos dos outros); avisos e comentários em separadores; botão de publicar fixo acima da barra inferior no telemóvel.
- 2026-11-17 — Estado offline passa a faixa no topo de todas as páginas (`html[data-online=false]`) — problema da app inteira, não de um cartão.
- 2026-11-17 — Tokens: novo `ember-strong` #c2410c para botões/texto laranja (o `ember` não passava AA com texto branco); `success` escurecido; botões e inputs com mínimo de 44 px e inputs a 16 px (sem zoom no iOS).
- 2026-11-17 — Desktop: os dois botões de reportar vivem no cabeçalho; no início só aparecem as contagens — evita dois pares de botões iguais lado a lado.
- 2026-11-17 — Service worker: páginas passam a network-first (cache só como fallback offline) — o cache-first mostrava HTML antigo depois de cada deploy.

## Open questions
- "Never like" e referências foram assumidos, não confirmados.
- Tema escuro faria sentido (uso à noite, sem luz) — fora do âmbito por agora (assumed).
