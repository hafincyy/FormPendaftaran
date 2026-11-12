<?php
/**
 * Registrasi Lomba Coding — Halaman Utama
 * 
 * Alur:
 *   1. Tampilkan form kosong (GET).
 *   2. Kirim form → validasi server-side (POST).
 *   3. Jika ada error  → tampilkan ulang form + pesan error, isian tetap terisi.
 *   4. Jika sukses     → tampilkan ringkasan data yang sudah aman (htmlspecialchars).
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/validate.php';

$errors  = [];
$data    = [];
$success = false;

// Proses form jika POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result  = validateForm($_POST);
    $errors  = $result['errors'];
    $data    = $result['data'];
    $success = empty($errors);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Lomba Coding</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="form-card">
    <header class="form-header">
        <h1>📋 Registrasi Lomba Coding</h1>
        <p>Isi formulir di bawah untuk mendaftar sebagai peserta</p>
    </header>

    <?php if ($success): ?>
        <!-- ====== Ringkasan Sukses ====== -->
        <div class="alert-success">
            <strong>✅ Pendaftaran Berhasil!</strong>
            <b>Nama:</b> <?= old('nama', $data) ?><br>
            <b>Email:</b> <?= old('email', $data) ?><br>
            <b>No. HP:</b> <?= old('hp', $data) ?><br>
            <b>Umur:</b> <?= old('umur', $data) ?> tahun<br>
            <?php if ($data['github'] !== ''): ?>
                <b>GitHub:</b> <?= old('github', $data) ?><br>
            <?php endif; ?>
            <b>Kategori:</b> <?= htmlspecialchars(KATEGORI_LIST[$data['kategori']], ENT_QUOTES, 'UTF-8') ?><br>
            <b>Peserta:</b> <?= htmlspecialchars(PESERTA_LIST[$data['peserta']], ENT_QUOTES, 'UTF-8') ?>
        </div>

        <a href="index.php" class="btn-submit" style="text-align:center; text-decoration:none; display:block;">Daftar Lagi</a>

    <?php else: ?>
        <!-- ====== Form Registrasi ====== -->
        <form id="formRegistrasi" method="POST" action="index.php" novalidate>

            <!-- Nama -->
            <div class="form-group">
                <label for="nama">Nama Lengkap <span class="required">*</span></label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>"
                    placeholder="Masukkan nama lengkap"
                    maxlength="50"
                    value="<?= old('nama', $data) ?>"
                >
                <span class="error-msg" id="error-nama"><?= $errors['nama'] ?? '' ?></span>
                <span class="hint">Minimal 3, maksimal 50 karakter.</span>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                    placeholder="contoh@email.com"
                    value="<?= old('email', $data) ?>"
                >
                <span class="error-msg" id="error-email"><?= $errors['email'] ?? '' ?></span>
            </div>

            <!-- Nomor HP -->
            <div class="form-group">
                <label for="hp">Nomor HP <span class="required">*</span></label>
                <input
                    type="text"
                    id="hp"
                    name="hp"
                    class="form-control <?= isset($errors['hp']) ? 'is-invalid' : '' ?>"
                    placeholder="08xxxxxxxxxx"
                    maxlength="13"
                    value="<?= old('hp', $data) ?>"
                >
                <span class="error-msg" id="error-hp"><?= $errors['hp'] ?? '' ?></span>
                <span class="hint">Format: 08xxxxxxxxxx (10–13 digit).</span>
            </div>

            <!-- Umur -->
            <div class="form-group">
                <label for="umur">Umur <span class="required">*</span></label>
                <input
                    type="number"
                    id="umur"
                    name="umur"
                    class="form-control <?= isset($errors['umur']) ? 'is-invalid' : '' ?>"
                    placeholder="17–19"
                    min="1"
                    max="99"
                    value="<?= old('umur', $data) ?>"
                >
                <span class="error-msg" id="error-umur"><?= $errors['umur'] ?? '' ?></span>
                <span class="hint">Peserta harus berusia 17–19 tahun.</span>
            </div>

            <!-- URL GitHub -->
            <div class="form-group">
                <label for="github">URL Repositori GitHub <span class="optional">(opsional)</span></label>
                <input
                    type="url"
                    id="github"
                    name="github"
                    class="form-control <?= isset($errors['github']) ? 'is-invalid' : '' ?>"
                    placeholder="https://github.com/username/repo"
                    value="<?= old('github', $data) ?>"
                >
                <span class="error-msg" id="error-github"><?= $errors['github'] ?? '' ?></span>
            </div>

            <!-- Kategori -->
            <div class="form-group">
                <label for="kategori">Kategori Lomba <span class="required">*</span></label>
                <select
                    id="kategori"
                    name="kategori"
                    class="form-control <?= isset($errors['kategori']) ? 'is-invalid' : '' ?>"
                >
                    <option value="">— Pilih Kategori —</option>
                    <?php foreach (KATEGORI_LIST as $key => $label): ?>
                        <option
                            value="<?= $key ?>"
                            <?= (($data['kategori'] ?? '') === $key) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="error-msg" id="error-kategori"><?= $errors['kategori'] ?? '' ?></span>
            </div>

            <!-- Jenis Peserta -->
            <div class="form-group">
                <label>Jenis Peserta <span class="required">*</span></label>
                <div class="radio-group">
                    <?php foreach (PESERTA_LIST as $key => $label): ?>
                        <label class="radio-item">
                            <input
                                type="radio"
                                name="peserta"
                                value="<?= $key ?>"
                                <?= (($data['peserta'] ?? '') === $key) ? 'checked' : '' ?>
                            >
                            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <span class="error-msg" id="error-peserta"><?= $errors['peserta'] ?? '' ?></span>
            </div>

            <button type="submit" class="btn-submit">Daftar Sekarang</button>
        </form>
    <?php endif; ?>
</div>

<script src="js/script.js"></script>
</body>
</html>
