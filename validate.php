<?php
/**
 * Validasi dan pemrosesan data formulir registrasi.
 * Mengembalikan array $errors (pesan error per field) dan $data (input bersih).
 */

require_once __DIR__ . '/config.php';

/**
 * Bersihkan input: trim dan strip tag.
 */
function sanitize(string $value): string
{
    return trim(strip_tags($value));
}

/**
 * Ambil nilai input yang sudah di-sanitize, aman untuk ditampilkan kembali.
 */
function old(string $field, array $data): string
{
    return htmlspecialchars($data[$field] ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Validasi seluruh data formulir.
 * 
 * @param  array $post Data $_POST
 * @return array [errors => [...], data => [...]]
 */
function validateForm(array $post): array
{
    $errors = [];
    $data   = [];

    // --- Nama ---
    $data['nama'] = sanitize($post['nama'] ?? '');
    $len = mb_strlen($data['nama']);
    if ($len === 0) {
        $errors['nama'] = 'Nama wajib diisi.';
    } elseif ($len < NAMA_MIN || $len > NAMA_MAX) {
        $errors['nama'] = "Nama harus antara " . NAMA_MIN . "–" . NAMA_MAX . " karakter (sekarang: {$len}).";
    }

    // --- Email ---
    $data['email'] = sanitize($post['email'] ?? '');
    if ($data['email'] === '') {
        $errors['email'] = 'Email wajib diisi.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Format email tidak valid.';
    }

    // --- Nomor HP ---
    $data['hp'] = sanitize($post['hp'] ?? '');
    if ($data['hp'] === '') {
        $errors['hp'] = 'Nomor HP wajib diisi.';
    } elseif (!preg_match(HP_PATTERN, $data['hp'])) {
        $errors['hp'] = 'Nomor HP tidak valid. Gunakan format 08xxxxxxxxxx (10–13 digit).';
    }

    // --- Umur ---
    $data['umur'] = sanitize($post['umur'] ?? '');
    if ($data['umur'] === '') {
        $errors['umur'] = 'Umur wajib diisi.';
    } elseif (!ctype_digit($data['umur'])) {
        $errors['umur'] = 'Umur harus berupa angka.';
    } else {
        $umur = (int) $data['umur'];
        if ($umur < UMUR_MIN || $umur > UMUR_MAX) {
            $errors['umur'] = "Umur harus antara " . UMUR_MIN . "–" . UMUR_MAX . " tahun. Anda tidak memenuhi syarat.";
        }
    }

    // --- URL GitHub (opsional) ---
    $data['github'] = sanitize($post['github'] ?? '');
    if ($data['github'] !== '') {
        if (!filter_var($data['github'], FILTER_VALIDATE_URL)) {
            $errors['github'] = 'URL tidak valid. Contoh: https://github.com/username/repo';
        } elseif (!preg_match('#^https?://(www\.)?github\.com/.+#i', $data['github'])) {
            $errors['github'] = 'URL harus merupakan link repositori GitHub.';
        }
    }

    // --- Kategori ---
    $data['kategori'] = sanitize($post['kategori'] ?? '');
    if ($data['kategori'] === '') {
        $errors['kategori'] = 'Kategori wajib dipilih.';
    } elseif (!array_key_exists($data['kategori'], KATEGORI_LIST)) {
        $errors['kategori'] = 'Kategori yang dipilih tidak valid.';
    }

    // --- Peserta ---
    $data['peserta'] = sanitize($post['peserta'] ?? '');
    if ($data['peserta'] === '') {
        $errors['peserta'] = 'Jenis peserta wajib dipilih.';
    } elseif (!array_key_exists($data['peserta'], PESERTA_LIST)) {
        $errors['peserta'] = 'Jenis peserta yang dipilih tidak valid.';
    }

    return ['errors' => $errors, 'data' => $data];
}
