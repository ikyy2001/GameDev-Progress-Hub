# Progress Tracker Game Dev

Website berbasis PHP untuk membantu game developer melacak perkembangan pembuatan game secara terstruktur, objektif, dan mudah dipahami.

## 📋 Deskripsi

Website ini dibuat untuk internal developer, mentor, guru, atau tim kecil yang mengembangkan game. Tujuan utama adalah mengubah **DATA** menjadi **INFORMASI PROGRESS** yang bermakna.

### Masalah yang Diselesaikan

- ✅ Developer sering bingung progres game sudah sampai mana
- ✅ Progress sering berdasarkan perasaan, bukan data
- ✅ Spreadsheet manual terlalu ribet dan tidak otomatis
- ✅ Sulit membuat laporan progress yang rapi

## 🏗️ Konsep Inti

Website bekerja dengan konsep **hierarki**:

```
Project (Game)
  └── Milestone (Bagian besar pengembangan)
       └── Task (Pekerjaan kecil dan konkret)
```

### Hierarki

1. **Project (Game)**
   - Representasi satu game
   - Memiliki overall progress (otomatis)
   - Memiliki status otomatis (Concept, Prototype, Alpha, Beta, Release)

2. **Milestone**
   - Bagian besar dari pengembangan game
   - Contoh: Core Gameplay, UI & UX, Visual, Bug Fixing, Audio
   - Progress milestone dihitung dari task di dalamnya

3. **Task**
   - Pekerjaan kecil dan konkret
   - Contoh: Player Movement, Inventory UI, Fix Datastore Bug
   - Task memiliki status dan checklist

## 🔧 Teknologi

- **Backend**: PHP (Native)
- **Frontend**: HTML, CSS, JavaScript (Vanilla)
- **Database**: MySQL
- **Server**: Apache/Nginx

## 📚 Dokumentasi & Panduan

### Untuk Pengguna
- **[USER_GUIDE.md](USER_GUIDE.md)** - 📖 Panduan lengkap penggunaan aplikasi
- **[QUICK_TUTORIAL.md](QUICK_TUTORIAL.md)** - ⚡ Tutorial cepat dalam 5 menit

### Untuk Developer
Lihat dokumentasi lengkap di folder `docs/`:

### Dokumentasi Utama
- **[INDEX.md](docs/INDEX.md)** - Daftar semua dokumentasi (mulai dari sini!)
- **[ARCHITECTURE.md](docs/ARCHITECTURE.md)** - Arsitektur sistem lengkap
- **[DATABASE.md](docs/DATABASE.md)** - Skema database dan ERD
- **[PROGRESS_LOGIC.md](docs/PROGRESS_LOGIC.md)** - Alur logika perhitungan progress
- **[PSEUDOCODE.md](docs/PSEUDOCODE.md)** - Pseudocode perhitungan progress
- **[STRUCTURE.md](docs/STRUCTURE.md)** - Struktur folder PHP lengkap
- **[INSTALLATION.md](INSTALLATION.md)** - Panduan instalasi step-by-step

## 🚀 Instalasi & Running

### Cara Cepat (PHP Built-in Server)

1. **Setup Database**
   - Buat database: `tracker_game_dev`
   - Import schema: `database/schema.sql`

2. **Konfigurasi Database**
   - Edit `config/database.php` (sesuaikan username/password MySQL)

3. **Jalankan Server**
   ```bash
   # Windows: Double-click run.bat
   # Atau manual:
   cd public
   php -S localhost:8000
   ```

4. **Akses di Browser**
   ```
   http://localhost:8000
   ```

5. **Login Default**
   - Username: `admin`
   - Password: `password123`

**📖 Lihat panduan lengkap:**
- [RUN.md](RUN.md) - Cara running detail
- [QUICK_START.md](QUICK_START.md) - Quick start guide
- [INSTALLATION.md](INSTALLATION.md) - Panduan instalasi lengkap

## 📁 Struktur Folder

```
/
├── config/          # Konfigurasi database dan aplikasi
├── database/        # File SQL schema
├── docs/            # Dokumentasi lengkap
├── public/          # File yang diakses public (index.php, assets)
├── src/             # Source code aplikasi
│   ├── controllers/ # Controller PHP
│   ├── models/      # Model database
│   ├── views/       # Template HTML/PHP
│   └── utils/       # Utility functions
└── README.md
```

## 👥 Role User

- **Owner**: Full access (CRUD semua)
- **Collaborator**: Bisa create/edit task dan milestone
- **Viewer**: Read-only access

## 📊 Fitur Utama

- ✅ Authentication (Login/Register)
- ✅ Project Management
- ✅ Milestone Management
- ✅ Task Management dengan tipe dan difficulty
- ✅ Dev Notes (catatan harian)
- ✅ Visualisasi progress bar
- ✅ Perhitungan progress otomatis

## 🎯 Tujuan Akhir

- Menjadi alat bantu berpikir untuk game developer
- Menyediakan laporan progress yang jelas
- Bisa digunakan untuk portofolio, laporan sekolah, dan kerja tim
- Bisa dikembangkan menjadi SaaS di masa depan

