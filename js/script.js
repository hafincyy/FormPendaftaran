/**
 * Registrasi Lomba Coding — Client-side validation (pelengkap server-side).
 */
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formRegistrasi');
    if (!form) return;

    const RULES = {
        nama:  { min: 3, max: 50 },
        umur:  { min: 17, max: 19 },
        hp:    /^08[1-9][0-9]{7,10}$/,
        github:/^https?:\/\/(www\.)?github\.com\/.+/i,
    };

    /**
     * Tampilkan/hapus pesan error pada satu field.
     */
    function setError(field, message) {
        const input   = form.querySelector(`[name="${field}"]`);
        const errorEl = document.getElementById(`error-${field}`);
        if (!input || !errorEl) return;

        if (message) {
            input.classList.add('is-invalid');
            errorEl.textContent = message;
        } else {
            input.classList.remove('is-invalid');
            errorEl.textContent = '';
        }
    }

    /**
     * Validasi real-time saat user mengetik / mengubah nilai.
     */
    function validateField(name, value) {
        value = value.trim();

        switch (name) {
            case 'nama': {
                if (!value) return 'Nama wajib diisi.';
                if (value.length < RULES.nama.min || value.length > RULES.nama.max)
                    return `Nama harus ${RULES.nama.min}–${RULES.nama.max} karakter.`;
                return '';
            }
            case 'email': {
                if (!value) return 'Email wajib diisi.';
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value))
                    return 'Format email tidak valid.';
                return '';
            }
            case 'hp': {
                if (!value) return 'Nomor HP wajib diisi.';
                if (!RULES.hp.test(value))
                    return 'Format: 08xxxxxxxxxx (10–13 digit).';
                return '';
            }
            case 'umur': {
                if (!value) return 'Umur wajib diisi.';
                const n = parseInt(value, 10);
                if (isNaN(n)) return 'Umur harus berupa angka.';
                if (n < RULES.umur.min || n > RULES.umur.max)
                    return `Umur harus ${RULES.umur.min}–${RULES.umur.max} tahun.`;
                return '';
            }
            case 'github': {
                if (!value) return ''; // opsional
                if (!RULES.github.test(value))
                    return 'URL harus link GitHub yang valid.';
                return '';
            }
            case 'kategori': {
                if (!value) return 'Kategori wajib dipilih.';
                return '';
            }
            case 'peserta': {
                if (!value) return 'Jenis peserta wajib dipilih.';
                return '';
            }
            default:
                return '';
        }
    }

    // Pasang event listener pada setiap input
    const fields = ['nama', 'email', 'hp', 'umur', 'github', 'kategori'];
    fields.forEach(name => {
        const el = form.querySelector(`[name="${name}"]`);
        if (!el) return;

        el.addEventListener('input', () => {
            setError(name, validateField(name, el.value));
        });

        el.addEventListener('blur', () => {
            setError(name, validateField(name, el.value));
        });
    });

    // Radio buttons
    const radios = form.querySelectorAll('[name="peserta"]');
    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            setError('peserta', '');
        });
    });

    // Validasi saat submit
    form.addEventListener('submit', (e) => {
        let hasError = false;

        fields.forEach(name => {
            const el  = form.querySelector(`[name="${name}"]`);
            const msg = validateField(name, el ? el.value : '');
            setError(name, msg);
            if (msg) hasError = true;
        });

        // Cek radio
        const pesertaChecked = form.querySelector('[name="peserta"]:checked');
        if (!pesertaChecked) {
            setError('peserta', 'Jenis peserta wajib dipilih.');
            hasError = true;
        }

        if (hasError) e.preventDefault();
    });
});
