<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Perpustakaan</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; 
            color: #333;
            line-height: 1.4;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }
        .header h2 { 
            margin: 0; 
            text-transform: uppercase;
            color: #1a1a1a;
        }
        .header p { margin: 5px 0 0; color: #666; }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f8f9fa; 
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        
        /* Zebra striping biar gak pusing bacanya */
        tr:nth-child(even) { background-color: #fafafa; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .badge {
            padding: 3px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-kembali { color: #155724; }
        .status-pinjam { color: #856404; }
        
        .denda-danger { color: #dc3545; font-weight: bold; }
        .denda-lunas { color: #28a745; }

        .footer { 
            margin-top: 30px; 
        }
        .total-box {
            float: right;
            width: 250px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            text-align: right;
        }
        .total-box h3 { margin: 0; color: #d32f2f; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN RIWAYAT TRANSAKSI PERPUSTAKAAN</h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="15%">Peminjam</th>
                <th width="25%">Judul Buku</th>
                <th width="12%" class="text-center">Tgl Pinjam</th>
                <th width="12%" class="text-center">Deadline</th>
                <th width="12%" class="text-center">Tgl Kembali</th>
                <th width="12%" class="text-right">Denda</th>
                <th width="9%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $key => $t)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td><strong>{{ $t->user->name }}</strong><br><span style="color:#888; font-size:9px;">ID: #{{ $t->user->id }}</span></td>
                <td>{{ $t->buku->judul }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d/m/Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($t->deadline)->format('d/m/Y') }}</td>
                <td class="text-center">
                    {{ $t->tanggal_kembali ? \Carbon\Carbon::parse($t->tanggal_kembali)->format('d/m/Y') : '-' }}
                </td>
                <td class="text-right">
                    @if($t->denda > 0)
                        <span class="denda-danger">Rp {{ number_format($t->denda, 0, ',', '.') }}</span>
                    @else
                        <span class="denda-lunas">Rp 0</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($t->status == 'dipinjam')
                        <span class="status-pinjam">PINJAM</span>
                    @else
                        <span class="status-kembali">KEMBALI</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="total-box">
            <span style="font-size: 10px; color: #666;">TOTAL PENDAPATAN DENDA</span>
            <h3>Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>