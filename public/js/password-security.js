/**
 * password-security.js
 *
 * Shared password validation for register and reset-password pages:
 *  - Requirements checklist (green ticks / red crosses)
 *  - Strength meter (Very Weak → Very Strong)
 *  - Password match indicator
 *  - HIBP breach check via AJAX on blur
 *
 * Expects these elements in the DOM:
 *  #password, #password_confirmation
 *  #pw-strength-fill, #pw-strength-label
 *  #pw-breach-result
 *  #req-length, #req-upper, #req-lower, #req-number, #req-special, #req-match
 *
 * Expects a global `breachCheckUrl` and `csrfToken` to be set in the page
 * (or falls back to meta tag for CSRF).
 */

document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const confirmInput  = document.getElementById('password_confirmation');
    const strengthFill  = document.getElementById('pw-strength-fill');
    const strengthLabel = document.getElementById('pw-strength-label');
    const breachResult  = document.getElementById('pw-breach-result');

    if (!passwordInput) return;

    const csrf = typeof csrfToken !== 'undefined'
        ? csrfToken
        : document.querySelector('meta[name="csrf-token"]')?.content || '';

    const breachUrl = typeof breachCheckUrl !== 'undefined'
        ? breachCheckUrl
        : '/password/check-breach';

    const requirements = {
        'req-length':  pw => pw.length >= 8,
        'req-upper':   pw => /[A-Z]/.test(pw),
        'req-lower':   pw => /[a-z]/.test(pw),
        'req-number':  pw => /[0-9]/.test(pw),
        'req-special': pw => /[@$!%*?&#]/.test(pw),
    };

    function updateRequirements(pw) {
        for (const [id, test] of Object.entries(requirements)) {
            const el   = document.getElementById(id);
            if (!el) continue;
            const icon = el.querySelector('i');
            const met  = test(pw);

            el.classList.toggle('met', met);
            el.classList.toggle('unmet', !met);
            if (icon) icon.className = met ? 'bi bi-check-circle-fill' : 'bi bi-x-circle';
        }
    }

    function updateMatch() {
        const el = document.getElementById('req-match');
        if (!el || !confirmInput) return;

        const icon = el.querySelector('i');
        const pw   = passwordInput.value;
        const conf = confirmInput.value;

        if (conf.length === 0) {
            el.classList.remove('met', 'unmet');
            if (icon) icon.className = 'bi bi-x-circle';
            return;
        }

        const match = pw === conf;
        el.classList.toggle('met', match);
        el.classList.toggle('unmet', !match);
        if (icon) icon.className = match ? 'bi bi-check-circle-fill' : 'bi bi-x-circle';
    }

    function updateStrength(pw) {
        if (!strengthFill || !strengthLabel) return;

        let score = 0;
        if (pw.length >= 8)  score++;
        if (pw.length >= 12) score++;
        if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
        if (/[0-9]/.test(pw)) score++;
        if (/[@$!%*?&#]/.test(pw)) score++;
        if (pw.length >= 16) score++;
        score = Math.min(score, 5);

        const levels = [
            { label: '',            color: 'transparent', width: '0%' },
            { label: 'Very Weak',   color: '#dc2626',     width: '20%' },
            { label: 'Weak',        color: '#ef4444',     width: '40%' },
            { label: 'Fair',        color: '#f59e0b',     width: '60%' },
            { label: 'Strong',      color: '#16a34a',     width: '80%' },
            { label: 'Very Strong', color: '#065f46',     width: '100%' },
        ];

        const level = levels[score];
        strengthFill.style.width           = level.width;
        strengthFill.style.backgroundColor = level.color;
        strengthLabel.textContent          = pw.length > 0 ? level.label : '';
        strengthLabel.style.color          = level.color;
    }

    let breachTimeout;
    function checkBreach() {
        const pw = passwordInput.value;
        if (!breachResult || pw.length < 8) {
            if (breachResult) breachResult.style.display = 'none';
            return;
        }

        breachResult.style.display = 'flex';
        breachResult.className = 'kb-pw-breach-inline checking';
        breachResult.innerHTML = '<i class="bi bi-arrow-repeat kb-spin"></i> <span>Checking against known data breaches…</span>';

        fetch(breachUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ password: pw }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.breached) {
                breachResult.className = 'kb-pw-breach-inline breached';
                breachResult.innerHTML =
                    '<i class="bi bi-shield-exclamation"></i>' +
                    '<span>This password was found in <strong>' +
                    Number(data.count).toLocaleString() +
                    '</strong> data breach(es). Please choose a different password!</span>';
            } else {
                breachResult.className = 'kb-pw-breach-inline safe';
                breachResult.innerHTML =
                    '<i class="bi bi-shield-check"></i>' +
                    '<span>This password has not been found in any known data breaches.</span>';
            }
        })
        .catch(() => { breachResult.style.display = 'none'; });
    }

    // Bind events
    passwordInput.addEventListener('input', function () {
        updateRequirements(this.value);
        updateStrength(this.value);
        updateMatch();
    });

    confirmInput?.addEventListener('input', updateMatch);

    passwordInput.addEventListener('blur', () => {
        clearTimeout(breachTimeout);
        breachTimeout = setTimeout(checkBreach, 300);
    });

    // Initial state
    updateRequirements('');
    updateStrength('');
});

// ── Password visibility toggle (global, used by register + reset) ──
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
