<!DOCTYPE html>
<html lang="en">

<head>
    <title>Karyawan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container mt-3">
        <h2 class="text-center">Data Karyawan</h2>
        <hr>
        <table class="table table-dark table-striped ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Nomor</th>
                    <th>Jabatan</th>
                    <th>Departement</th>
                    <th>Tanggal Masuk</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $data as $v )
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$v->nama}}</td>
                    <td>{{$v->nomor}}</td>
                    <td>{{$v->jabatan}}</td>
                    <td>{{$v->departement}}</td>
                    <td>{{$v->tanggal_masuk}}</td>
                    <td>{{$v->status}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
