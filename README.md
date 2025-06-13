---
# TUGAS BESAR DEVSECOPS
---

## Anggota Tim
- **Yozarino Hady** - 1103220189
- **Yudha Afriza Revi** - 1103223032
- **Kiay Ahmadjaya Cendekia** - 1103223079
- **Khalid Nurahman** - 1103223102
- **Bagus Fatkhurrohman** - 1103223195

---

## Arsitektur Aplikasi

### Komponen Tech Stack

| Layer | Tech |
|-------|------------|
| **Frontend** | HTML, CSS, JavaScript |
| **Backend** | PHP, Apache2 |
| **Database** | MySQL (init.sql) |
| **Infrastruktur** | Docker (Dockerfile), Jenkins (Jenkinsfile) |
| **Version Control** | Git |

---

## Implementasi DevSecOps

### Pengembangan
- **Runtime:** PHP 8.1 dengan Apache Web Server
- **Dependency Management:** Composer
- **Version Control:** Git

### Keamanan
- **Keamanan Aplikasi:**
  - Perlindungan CSRF
  - Password Hashing
  - Rate limiting
  - Sessin Management
  - Validasi input
  - XSS Prevention

- **Keamanan Infrastruktur:**
  - Isolasi kontainer Docker
  - Apache Security Header

- **Security Scanning:**
  - Built-in PHP Linting
  - Pattern-based vulnerability detection

### Operasi
- **Kontainerisasi:** Docker dengan image dasar php8.1-apache
- **Platform CI/CD:** Jenkins dengan Jenkinsfile berbasis Groovy
- **Deployment:** Multi-strategi (penerapan file tradisional + kontainerisasi)
- **Monitoring:** Docker health check, Apache logging

---

## Tahapan Pipeline CI/CD

Pipeline Jenkins mengeksekusi 8 tahapan berurutan:

1. **Start**
2. **Setup Environment**
3. **Checkout**
4. **Install Dependencies**
5. **Build assets**
6. **Run Tests**
7. **Deploy**
8. **End**

**Detail Penerapan:**
- Menggunakan Node.js 18
- Menerapkan file ke direktori `/var/www/your-project`
- Status: "Pipeline dieksekusi dengan sukses" (semua tahapan selesai tanpa kesalahan)

---

## Pengujian Keamanan (SAST/DAST)

### Kerentanan yang Teridentifikasi dan Mitigasi

| Kerentanan | Contoh Serangan | Strategi Mitigasi |
|---------------|----------------|-------------------|
| **SQL Injection** | `username: ' OR 1=1 --`<br>`password: asdf` | **Prepared Statements** - Memastikan input pengguna diperlakukan sebagai data, bukan perintah yang dapat dieksekusi |
| **XSS (Cross-Site Scripting)** | `<script>alert(1)</script>` | **Output Encoding** (HTML-escaping) dan **Validasi Input Ketat** |
| **Session Fixation** | - | Gunakan `session_regenerate_id(true);` di `authenticate.php` setelah login berhasil |
| **Brute Force** | - | **Mekanisme penguncian sementara berbasis sesi** |

---

## Detail Infrastruktur

### Lapisan Aplikasi

| Lapisan | Implementasi |
|-------|----------------|
| **Web** | Apache HTTP Server dalam Kontainer Docker |
| **Aplikasi** | Runtime PHP 8.1 dengan penerapan kontainerisasi |
| **Data** | Basis Data MySQL |
| **CI/CD** | Pipeline Jenkins |

---

## Jobdesk Anggota Kelompok

| Anggota Tim | NIM | Jobdesk |
|-------------|-----|------------------|
| **Yozarino Hady** | 1103220189 | Dokumentasi (tutormas.md, README.md, TODO.md), kode aplikasi awal, proses pembangunan npm |
| **Yudha Afriza Revi** | 1103223032 | Pentesting, hardning code php,,membuat database,mengedit + memperbaiki error di db |
| **Kiay Ahmadjaya Cendekia** | 1103223079 | Mengerjakan dan membuat Pipeline stages, Membuat laporan CI/CD Stage Pipeline |
| **Khalid Nurahman** | 1103223102 | Konfigurasi Jenkins, Mengupdate isi Jenkinsfile, Membuat TODO untuk bagian deployment, Mengerjakan dan membuat Pipeline stages Pada Jenkins |
| **Bagus Fatkhurrohman** | 1103223195 | Release,membuat database,mengedit + memperbaiki error di db |

---

## Referensi
- Aplikasi [PHP](https://gitlab.com/cretoxyrhina/phplogin)
- Dokumentasi instal [Docker](https://docs.docker.com/engine/install/ubuntu/)
- Dokumentasi instal [Jenkins](https://www.jenkins.io/doc/book/installing/docker/) di dalam docker container
- 
