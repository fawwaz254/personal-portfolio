# Personal Portfolio

Website portofolio pribadi untuk memperkenalkan profil, keterampilan, proyek, dan pengalaman saya di bidang teknologi.

Project ini dikembangkan sebagai ruang untuk menampilkan hasil karya, mengeksplorasi teknologi, serta mendokumentasikan proses belajar dan pengembangan diri di bidang teknologi informasi.


## Fitur Utama

- **Beranda** — perkenalan singkat dan gambaran profil.
- **Tentang Saya** — informasi mengenai latar belakang dan minat profesional.
- **Keahlian** — teknologi dan keterampilan yang sedang dikembangkan.
- **Proyek** — dokumentasi proyek yang pernah dikerjakan.
- **Kontak** — formulir untuk mengirim pesan.

## Teknologi

### Frontend
- Vue 3
- Vite
- Tailwind CSS
- Lucide Icons

### Backend
- Laravel
- PHP
- MySQL, jika digunakan oleh konfigurasi project

## Struktur Project

```text
portfolio/
├── frontend/
│   ├── public/
│   ├── src/
│   ├── .env.example
│   └── package.json
└── backend/
    ├── app/
    ├── routes/
    ├── database/
    └── composer.json
```

*Struktur di atas merupakan gambaran umum. Sesuaikan dengan struktur folder aktual repository.*

## Instalasi dan Menjalankan Project

### 1. Clone Repository

```bash
git clone <URL_REPOSITORY>
cd <NAMA_FOLDER_PROJECT>
```

### 2. Menjalankan Frontend

```bash
cd frontend
npm install
```

Buat file `.env` berdasarkan `.env.example`, kemudian atur URL API backend.

```env
VITE_API_BASE_URL=http://127.0.0.1:8000/api
```

Jalankan frontend:

```bash
npm run dev
```

### 3. Menjalankan Backend

Buka terminal baru, kemudian jalankan:

```bash
cd backend
composer install
```

Salin `.env.example` menjadi `.env`, lalu sesuaikan konfigurasi database jika diperlukan.

```bash
php artisan key:generate
php artisan migrate
php artisan serve
```

Pastikan database sudah dibuat dan konfigurasi koneksi telah sesuai sebelum menjalankan migrasi.

## Pengembangan

Project ini dapat dikembangkan lebih lanjut melalui penambahan fitur, penyempurnaan antarmuka, peningkatan validasi, serta pengujian untuk menjaga kualitas dan keandalan aplikasi.

## Kontak

Silakan kunjungi profil GitHub atau hubungi saya melalui informasi kontak yang tersedia pada website portofolio.

---

Dikembangkan sebagai bagian dari perjalanan belajar dan pengembangan diri di bidang teknologi informasi.