import { Controller } from '../vendor/@hotwired/stimulus/stimulus.index.js';

export default class extends Controller {
    static values = {
        mode:     String,
        start:    Number,
        duration: { type: Number, default: 0 },
        warning:  { type: Number, default: 0 },
        error:    { type: Number, default: 0 },
    }

    connect() {
        this.tick();
        this.interval = setInterval(() => this.tick(), 1000);
    }

    disconnect() {
        clearInterval(this.interval);  // kein Memory Leak
    }

    tick() {
        const now = Date.now();
        const s   = this.modeValue === 'timer'
            ? Math.floor((now - this.startValue) / 1000)
            : Math.max(0, Math.floor((this.startValue + this.durationValue - now) / 1000));

        this.element.textContent = this.format(s);
        this.element.classList.toggle('tc-timer-warning', this.warningValue > 0 && s * 1000 >= this.warningValue);
        this.element.classList.toggle('tc-timer-error',   this.errorValue   > 0 && s * 1000 >= this.errorValue);
    }

    format(s) {
        const h = Math.floor(s / 3600), m = Math.floor((s % 3600) / 60), sec = s % 60;
        return [h, m, sec].map(n => String(n).padStart(2, '0')).join(':');
    }
}
