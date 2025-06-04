# JANLUP DOKUMENTASI

## BUILD
### Script
Instal npm
```
sudo apt install npm
```
Instal front dependencies npm
```
npm install bootstrap@5 jquery@3
```
Inisialisasi npm
```
npm init -y
```
di dalam <b>package.json</b>, masukkan baris kode ini ke dalam bagian "scripts":
```
"build": "mkdir -p public/css public/js && cp node_modules/bootstrap/dist/css/bootstrap.min.css public/css/ && cp node_modules/bootstrap/dist/js/bootstrap.bundle.min.js public/js/ && cp node_modules/jquery/dist/jquery.min.js public/js/",
"start": "npm run build && echo 'Project built! Run your PHP server manually'"
```
Masukkan kode ini kedalam bagian header pada setiap php yang mengandung html
```
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="/css/bootstrap.min.css">
```
Masukkan kode ini kedalam bagian body pada setiap php yang mengandung html
```
<!-- jQuery & Bootstrap JS -->
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
```
Build
```
npm run build
```
### Konfigurasi mysqli-connect
Instal php-mysqli
```
sudo apt install php-mysqli
```
Restart apache2 service
```
systemctl restart apache2
```
buka file <b>php.ini</b> dengan privilege root (disarankan pakai aplikasi text editor seperti sublime-text)
```
sudo subl /etc/php/8.1/cli/php.ini
```
Carilah baris kode *;extension=mysqli* (pada kasus ini, berada di line 930) lalu hapus komentar (;) yang berada di depan kode tersebut, sehingga hasil akhir akan terlihat seperti ini
```
;extension=mbstring
;extension=exif      ; Must be after mbstring as it depends on it
extension=mysqli
;extension=oci8_12c  ; Use with Oracle Database 12c Instant Client
;extension=oci8_19  ; Use with Oracle Database 19 Instant Client
```
### Konfigurasi hak akses session
Defaultnya, session akan tersimpan di
```
/var/lib/php/sessions
```
Ubah hak akses direktori tersebut menggunakan command
```
sudo chown -R www-data:www-data /var/lib/php/sessions
```

## Test
Instal OWASP ZAP nya gimana
```

```

## Release
Instal Jenkins nya gimana
```

```

## Deploy
Deploy pake Jenkins nya gimana
```

```

## Operate
Instal dockernya gimana
```

```

## Monitor
Instal prometheus nya gimana
```

```
