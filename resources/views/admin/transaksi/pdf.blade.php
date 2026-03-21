<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Perpustakaan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .total { margin-top: 20px; font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN TRANSAKSI PERPUSTAKAAN DIGITAL</h2>
        <p>Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Deadline</th>
                <th>Tgl Kembali</th>
                <th>Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $key => $t)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $t->user->name }}</td>
                <td>{{ $t->buku->judul }}</td>
                
                {{-- Kita bungkus pake Carbon::parse biar gak error lagi --}}
                <td>{{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($t->deadline)->format('d/m/Y') }}</td>
                <td>
                    {{ $t->tanggal_kembali ? \Carbon\Carbon::parse($t->tanggal_kembali)->format('d/m/Y') : '-' }}
                </td>
                
                <td>Rp {{ number_format($t->denda, 0, ',', '.') }}</td>
                <td>{{ ucfirst($t->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Pendapatan Denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}
    </div>
</body>
</html>