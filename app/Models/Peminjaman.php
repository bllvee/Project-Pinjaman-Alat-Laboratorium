<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'barang_id',
        'tanggal_pinjam',
        'tanggal_rencana_kembali',
        'tanggal_kembali_aktual',
        'kondisi_saat_kembali',
        'hari_terlambat',
        'denda_keterlambatan',
        'biaya_perbaikan',
        'total_denda',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam'          => 'date',
        'tanggal_rencana_kembali' => 'date',
        'tanggal_kembali_aktual'  => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
