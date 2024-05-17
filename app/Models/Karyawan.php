<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'karyawans';
    protected $fillable = 
    ['nama', 
     'nomor', 
     'jabatan', 
     'departement', 
     'tanggal_masuk', 
     'foto', 
     'status'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
