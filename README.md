--Project Test Backend CRUD Export Import--

1. Membuat Database Bernama "test_backend"

2. .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test_backend
DB_USERNAME=root
DB_PASSWORD=

3. php artisan migrate

4. php artisan serve

5. LINK API:
Get Karyawan:
localhost:8000/api/karyawan?limit=5&page=1

Create Karyawan:
localhost:8000/api/karyawan
Body Create: {
	"nama":"",
	"nomor":"",
	"jabatan":"",
	"departement":"",
	"tanggal_masuk":"",
    "status": "",
    "foto": ""
}

Update Karyawan:
localhost:8000/api/karyawan/{id}
Body Update: {
    "nama":"",
	"nomor":"",
	"jabatan":"",
	"departement":"",
	"tanggal_masuk":"",
    "status": "",
    "foto": ""
}

Delete Karyawan: 
localhost:8000/api/karyawan/{id}


Import Karyawan: 
localhost:8000/api/karyawan/import
Body: {
    "file" : "",
}

Export Csv Karyawan: 
localhost:8000/api/karyawan/export-csv

Export PDF Karyawan: 
localhost:8000/api/karyawan/export-pdf