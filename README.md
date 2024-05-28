--Project Test Backend CRUD Export Import--
# Heading 1

# Heading 1
## Heading 2
### Heading 3

Emphasis, aka italics, with *asterisks* or _underscores_.

Strong emphasis, aka bold, with **asterisks** or __underscores__.

Combined emphasis with **asterisks and _underscores_**.
1. First ordered list item
2. Another item
⋅⋅* Unordered sub-list. 
1. Actual numbers don't matter, just that it's a number
⋅⋅1. Ordered sub-list
4. And another item.

[I'm an inline-style link](https://www.google.com)

[I'm an inline-style link with title](https://www.google.com "Google's Homepage")

![descriptive alt text](https://github.com/adam-p/markdown-here/raw/master/src/common/images/icon48.png "Logo Title Text 1")
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
