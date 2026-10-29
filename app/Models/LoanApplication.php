<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_code',
        'nik',
        'nama',
        'alamat',
        'hp',
        'afiliasi',
        'nominal',
        'tenor',
        'tujuan',
        'sumber_pendapatan',
        'pendapatan_tetap',
        'pendapatan_lain',
        'deskripsi_lain',
        'agunan',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'tenor' => 'integer',
            'pendapatan_tetap' => 'decimal:2',
        ];
    }

    public static function generateReferenceCode(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return "BM-{$date}-{$random}";
    }
}
