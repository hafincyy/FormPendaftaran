<?php
/**
 * Konfigurasi dan konstanta untuk Formulir Registrasi Lomba Coding.
 */

// Whitelist kategori lomba
define('KATEGORI_LIST', [
    'web'      => 'Web Development',
    'mobile'   => 'Mobile Development',
    'ai'       => 'Artificial Intelligence',
    'game'     => 'Game Development',
    'security' => 'Cyber Security',
]);

// Whitelist jenis peserta
define('PESERTA_LIST', [
    'individu' => 'Individu',
    'tim'      => 'Tim (Maks. 3 Orang)',
]);

// Batas validasi
define('NAMA_MIN', 3);
define('NAMA_MAX', 50);
define('UMUR_MIN', 17);
define('UMUR_MAX', 19);

// Pattern nomor HP Indonesia: 08xx-xxxx-xxxx (10-13 digit)
define('HP_PATTERN', '/^08[1-9][0-9]{7,10}$/');
