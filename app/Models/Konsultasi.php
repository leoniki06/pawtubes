<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    protected $table = 'konsultasi';

    protected $fillable = [
        'nama_pemilik',
        'nama_hewan',
        'foto_hewan',
        'kategori_hewan',
        'ras',
        'jenis_kelamin',
        'usia_hewan',
        'kontak',
    ];

    public $timestamps = false; // Nonaktifkan timestamps
}
