# Struktur Lengkap Project PPDB Man-Jeneponto

```
.editorconfig
.gitattributes
.gitignore
artisan
composer.json
composer.lock
package-lock.json
package.json
phpunit.xml
README.md
vite.config.js
app/
├── Http/
│   ├── Controllers/
│   │   ├── Controller.php
│   │   ├── Admin/
│   │   ├── Auth/
│   │   ├── Panitia/
│   │   ├── Ppdb/
│   │   └── Website/
│   └── Middleware/
│       └── CheckPpdbStep.php
├── Models/
│   ├── Pendaftaran.php
│   ├── PpdbUser.php
│   └── User.php
└── Providers/
    └── AppServiceProvider.php
bootstrap/
├── app.php
├── providers.php
└── cache/
    └── .gitignore
config/
├── app.php
├── auth.php
├── cache.php
├── database.php
├── filesystems.php
├── logging.php
├── mail.php
├── queue.php
├── services.php
└── session.php
database/
├── .gitignore
├── factories/
│   └── UserFactory.php
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 0001_01_01_000001_create_cache_table.php
│   ├── 0001_01_01_000002_create_jobs_table.php
│   ├── 2026_03_13_160826_create_ppdb_users_table.php
│   ├── 2026_03_16_035939_create_pendaftaran_table.php
│   ├── 2026_03_16_041332_add_berkas_to_pendaftaran_table.php
│   ├── 2026_03_17_161306_add_status_to_pendaftaran_table.php
│   ├── 2026_03_19_071750_add_email_verified_to_ppdb_users.php
│   └── 2026_03_19_093006_add_last_step_to_pendaftaran_table.php
└── seeders/
    └── DatabaseSeeder.php
public/
├── .htaccess
├── index.php
├── man.svg
├── robots.txt
└── ppdb/
    ├── afirmasi.svg
    ├── afirmasi2.svg
    ├── benner.svg
    ├── bgbiru.svg
    ├── bgLogin.svg
    ├── biru.svg
    ├── Breadscrup.svg
    ├── ceklis.jpg
    ├── ceklis.svg
    ├── daftar.svg
    ├── daftarulang.jpg
    ├── daftarulang.svg
    ├── daftarulang2.svg
    ├── diterima.png
    ├── ditolak.png
    ├── final.jpg
    ├── final.svg
    ├── Group 18.svg
    ├── Group 1984078615.png
    ├── Ibu.svg
    ├── isiformulir.jpg
    ├── isiformulir.svg
    ├── landingpage.svg
    ├── Loginbg.svg
    ├── logoman.png
    ├── man.svg
    ├── manjepot.png
    ├── menunggu.png
    ├── pengumuman.jpg
    ├── pengumuman.svg
    ├── pengumuman2.svg
    ├── perbaikan.png
    ├── popupnisn.svg
    ├── prestasi2.svg
    ├── putih.svg
    ├── regular.svg
    ├── regular2.svg
    ├── seleksiadministrasi.svg
    ├── siswalogin.svg
    ├── terima.png
    ├── tidaklolos.png
    ├── upload.png
    ├── uploadberkas.jpg
    ├── uploadberkas.svg
    ├── verivdata.jpg
    └── verivdata.svg
    └── admin/
        ├── daftarulang.png
        ├── dashboard.png
        ├── delate.png
        ├── detail.png
        ├── edit.png
        ├── hapus.png
        ├── jadwalpendaftaran.png
        ├── jaluraktif.png
        ├── keluar.png
        ├── manajemensistem.png
        ├── masterppdb.png
        ├── notifikasi.png
        ├── operasional.png
        ├── pendaftarhariini.png
        ├── pengumuman.png
        ├── perluverifikasi.png
        ├── seleksiadminstarsi.png
        └── totalpendaftar.png
        └── operasional/
    resources/
    ├── css/
    │   └── app.css
    └── js/
        ├── app.js
        └── bootstrap.js
    └── views/
        ├── welcome.blade.php
        ├── admin/
        │   ├── dashboard.blade.php
        │   ├── pengguna/
        │   ├── ppdb/
        │   └── website/
        ├── auth/
        │   └── login.blade.php
        ├── components/
        │   ├── admin/
        │   ├── panitia/
        │   ├── ppdb/
        │   └── website/
        ├── layouts/
        │   ├── admin.blade.php
        │   ├── app.blade.php
        │   ├── index.html
        │   ├── panitia.blade.php
        │   ├── ppdb-public.blade.php
        │   ├── ppdb-siswa.blade.php
        │   └── website.blade.php
        ├── panitia/
        │   ├── dashboard.blade.php
        │   └── {data-pendaftar,verifikasi-berkas,catatan-verifikasi,nilai-seleksi,hasil-seleksi}/
        ├── ppdb/
        │   └── {auth,dashboard,pendaftaran,berkas,pengumuman,daftar-ulang}/
        │       ├── auth/
        │       ├── berkas/
        │       ├── daftar-ulang/
        │       ├── dashboard/
        │       ├── partials/
        │       ├── pendaftaran/
        │       └── pengumuman/
        └── website/
            ├── beranda.blade.php
            ├── berita/
            ├── galeri/
            ├── hubungi-kami/
            ├── kesiswaan/
            ├── kurikulum/
            └── layanan/
    routes/
    ├── console.php
    └── web.php
    storage/
    ├── app/
    │   └── .gitignore
    ├── framework/
    │   ├── .gitignore
    │   ├── cache/
    │   ├── sessions/
    │   ├── testing/
    │   └── views/
    └── logs/
        └── .gitignore
    tests/
    ├── TestCase.php
    ├── Feature/
    │   └── ExampleTest.php
    └── Unit/
        └── ExampleTest.php
```

## Catatan:
- Struktur ini menampilkan seluruh file dan direktori secara rekursif dari root project.
- Direktori kosong atau dengan placeholder seperti `{...}` menunjukkan adanya subdirektori/file lebih lanjut.
- File ini siap untuk di-copy paste.

Salin struktur di atas sesuai kebutuhan Anda!
```

