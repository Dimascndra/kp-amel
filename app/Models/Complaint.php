<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code',
        'nama',
        'email',
        'telepon',
        'nomor_rekening',
        'kategori',
        'judul',
        'isi_pengaduan',
        'lampiran',
        'status',
        'catatan_petugas',
        'ditangani_oleh',
        'tanggal_ditindaklanjuti',
        'tanggal_selesai',
        'ip_address',
        'user_agent',
        'fraud_risk_level',
        'fraud_notes',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_ditindaklanjuti' => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    public static function generateTicketCode(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return "BM-ADU-{$date}-{$random}";
    }

    public function scopeDiproses($query)
    {
        return $query->where('status', 'diproses');
    }

    public function scopeDitindaklanjuti($query)
    {
        return $query->where('status', 'ditindaklanjuti');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'diproses' => 'Diproses',
            'ditindaklanjuti' => 'Ditindaklanjuti',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'diproses' => 'bg-amber-100 text-amber-800 border-amber-300',
            'ditindaklanjuti' => 'bg-blue-100 text-blue-800 border-blue-300',
            'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'ditolak' => 'bg-red-100 text-red-800 border-red-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
