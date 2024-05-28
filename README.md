# Project Test Backend CRUD Export Import

## Step 1
Membuat Database Bernama "test_backend"

## Step 2
.env yang berisikan:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test_backend
DB_USERNAME=root
DB_PASSWORD=
```

## Step 3
php artisan migrate

## Step 4
php artisan serve

## Step 5
Link API:

Get Karyawan: <br>
* Method GET localhost:8000/api/karyawan?limit=5&page=1 


Create Karyawan: <br>
* Method POST localhost:8000/api/karyawan
Body Create: 
```json
{
"nama":"",
"nomor":"",
"jabatan":"",
"departement":"",
"tanggal_masuk":"",
"status": "",
"foto": ""
}
```


Update Karyawan: <br>
* Method PUT localhost:8000/api/karyawan/{id}
Body Update:
```json
 {
"nama":"",
"nomor":"",
"jabatan":"",
"departement":"",
"tanggal_masuk":"",
"status": "",
"foto": ""
}
```


Delete Karyawan: <br>
* Method DELETE localhost:8000/api/karyawan/{id}


Import Karyawan: 
* Method POST localhost:8000/api/karyawan/import <br>
Body:
```json
 {
    "file" : "",
}
```


Export Csv Karyawan: 
* Method GET localhost:8000/api/karyawan/export-csv

Export PDF Karyawan: 
* Method GET localhost:8000/api/karyawan/export-pdf
