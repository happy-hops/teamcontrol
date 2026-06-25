function pad(n) { return String(n).padStart(2, '0'); }

function fmt(s) {
    const h = Math.floor(s / 3600);
    const m = Math.floor((s % 3600) / 60);
    const sec = s % 60;
    return `${pad(h)}:${pad(m)}:${pad(sec)}`;
}

function initRaceBar() {
    const bar = document.getElementById('tc-race-bar');
    if (!bar) return;

    const started = parseInt(bar.dataset.started) * 1000;
    const durationMs = parseInt(bar.dataset.duration) * 1000;
    const elapsedEl = document.getElementById('tc-elapsed');
    const remainEl = document.getElementById('tc-remaining');
    const fillEl = document.getElementById('tc-progress');

    document.body.classList.add('has-race-bar');
    if (!document.querySelector('.tc-navbar')) {
        document.body.classList.add('no-navbar');
    }

    function tick() {
        const now = Date.now();
        const elapsedMs = now - started;
        const remainMs = Math.max(0, durationMs - elapsedMs);
        const elapsedSec = Math.floor(elapsedMs / 1000);
        const remainSec = Math.floor(remainMs / 1000);

        if (elapsedEl) elapsedEl.textContent = fmt(elapsedSec);
        if (remainEl) remainEl.textContent = fmt(remainSec);

        if (fillEl) {
            fillEl.style.width = Math.min(100, (elapsedMs / durationMs) * 100).toFixed(3) + '%';
        }

        if (remainEl) {
            const ratio = remainMs / durationMs;
            remainEl.classList.toggle('tc-bar-warning', ratio < 0.1 && ratio >= 0.05);
            remainEl.classList.toggle('tc-bar-critical', ratio < 0.05);
        }

        if (remainMs <= 0) clearInterval(interval);
    }

    tick();
    const interval = setInterval(tick, 1000);
}

document.addEventListener('DOMContentLoaded', initRaceBar);
