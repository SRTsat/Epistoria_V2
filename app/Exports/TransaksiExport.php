<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $tahun;

    // 1. Tambahin constructor biar bisa nerima lemparan tahun dari Controller
    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function collection()
    {
        // 2. Filter data berdasarkan tahun yang dipilih
        return Peminjaman::with(['user', 'buku'])
            ->whereYear('created_at', $this->tahun) // Filter tahun di sini
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return ['No', 'Nama Peminjam', 'Judul Buku', 'Tgl Pinjam', 'Deadline', 'Tgl Kembali', 'Denda', 'Status'];
    }

    public function map($t): array
    {
        static $no = 0;
        return [
            ++$no,
            $t->user->name ?? 'User Terhapus', // Kasih fallback biar gak error kalo user ilang
            $t->buku->judul ?? 'Buku Terhapus',
            $t->tanggal_pinjam,
            $t->deadline,
            $t->tanggal_kembali ?? '-',
            'Rp ' . number_format($t->denda, 0, ',', '.'),
            ucwords(str_replace('_', ' ', $t->status)) // Biar 'proses_kembali' jadi 'Proses Kembali'
        ];
    }
}