<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Panggil relasi biar gak N+1 query
        return Peminjaman::with(['user', 'buku'])->latest()->get();
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
            $t->user->name,
            $t->buku->judul,
            $t->tanggal_pinjam,
            $t->deadline,
            $t->tanggal_kembali ?? '-',
            'Rp ' . number_format($t->denda, 0, ',', '.'),
            ucwords($t->status)
        ];
    }
}