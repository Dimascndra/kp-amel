<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengaduan Nasabah Diterima - Bank Majalengka</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Plus Jakarta Sans', Arial, sans-serif; color: #1e293b; line-height: 1.6;">

  <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f1f5f9; padding: 24px 12px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.06); max-width: 600px; width: 100%;">
          
          <!-- Header Banner -->
          <tr>
            <td style="background-color: #002D62; padding: 28px 32px; text-align: left;">
              <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td>
                    <span style="display: inline-block; font-size: 11px; font-weight: 800; color: #F59E0B; letter-spacing: 2px; text-transform: uppercase;">
                      PERUMDA BPR MAJALENGKA
                    </span>
                    <h1 style="color: #ffffff; margin: 4px 0 0; font-size: 20px; font-weight: 800;">
                      Konfirmasi Penerimaan Pengaduan
                    </h1>
                  </td>
                  <td align="right" style="vertical-align: middle;">
                    <div style="background-color: rgba(255,255,255,0.15); color: #F59E0B; font-weight: 900; font-size: 16px; padding: 8px 14px; border-radius: 10px; display: inline-block;">
                      BM
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Main Content -->
          <tr>
            <td style="padding: 32px;">
              <p style="font-size: 14px; color: #475569; margin: 0 0 16px;">
                Yth. Bapak/Ibu <strong style="color: #0f172a;">{{ $complaint->nama }}</strong>,
              </p>
              <p style="font-size: 14px; color: #475569; margin: 0 0 20px;">
                Terima kasih telah menghubungi Perumda BPR Majalengka. Pengaduan dan laporan Anda telah resmi kami terima dan dicatat ke dalam sistem penanganan keluhan nasabah.
              </p>

              <!-- Ticket Code Box -->
              <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; border: 2px dashed #0066CC; border-radius: 12px; margin: 20px 0;">
                <tr>
                  <td style="padding: 20px; text-align: center;">
                    <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 6px;">
                      KODE UNIK PENGADUAN ANDA
                    </span>
                    <span style="font-family: 'JetBrains Mono', Consolas, monospace; font-size: 22px; font-weight: 800; color: #002D62; letter-spacing: 2px; display: inline-block;">
                      {{ $complaint->ticket_code }}
                    </span>
                    <p style="font-size: 12px; color: #64748b; margin: 8px 0 0;">
                      Simpan kode unik di atas untuk melacak perkembangan status laporan Anda di website resmi Bank Majalengka.
                    </p>
                  </td>
                </tr>
              </table>

              <!-- Status Pipeline Explainer -->
              <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 24px 0 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                Alur Tahapan Penanganan Pengaduan:
              </h3>
              
              <table width="100%" cellpadding="0" cellspacing="0" style="font-size: 13px; line-height: 1.5; margin-bottom: 24px;">
                <!-- Step 1: Diproses -->
                <tr>
                  <td style="width: 28px; vertical-align: top; padding-bottom: 12px;">
                    <span style="background-color: #F59E0B; color: #ffffff; font-weight: bold; font-size: 11px; width: 22px; height: 22px; border-radius: 50%; display: inline-block; text-align: center; line-height: 22px;">1</span>
                  </td>
                  <td style="padding-bottom: 12px;">
                    <strong style="color: #0f172a;">Diproses <span style="background-color: #fef3c7; color: #92400e; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-left: 4px;">Status Saat Ini</span></strong>
                    <div style="color: #64748b; font-size: 12px; margin-top: 2px;">
                      Pengaduan telah masuk ke sistem dan sedang diteliti validitas kronologi serta bukti awal oleh Bagian Layanan Nasabah & Kepatuhan.
                    </div>
                  </td>
                </tr>

                <!-- Step 2: Ditindaklanjuti -->
                <tr>
                  <td style="width: 28px; vertical-align: top; padding-bottom: 12px;">
                    <span style="background-color: #94a3b8; color: #ffffff; font-weight: bold; font-size: 11px; width: 22px; height: 22px; border-radius: 50%; display: inline-block; text-align: center; line-height: 22px;">2</span>
                  </td>
                  <td style="padding-bottom: 12px;">
                    <strong style="color: #334155;">Ditindaklanjuti</strong>
                    <div style="color: #64748b; font-size: 12px; margin-top: 2px;">
                      Laporan sedang diinvestigasi secara mendalam dan dikoordinasikan dengan unit kerja / kantor kas terkait untuk penanganan solusi teknis.
                    </div>
                  </td>
                </tr>

                <!-- Step 3: Selesai -->
                <tr>
                  <td style="width: 28px; vertical-align: top;">
                    <span style="background-color: #94a3b8; color: #ffffff; font-weight: bold; font-size: 11px; width: 22px; height: 22px; border-radius: 50%; display: inline-block; text-align: center; line-height: 22px;">3</span>
                  </td>
                  <td>
                    <strong style="color: #334155;">Selesai</strong>
                    <div style="color: #64748b; font-size: 12px; margin-top: 2px;">
                      Hasil tindak lanjut dan solusi resmi telah ditetapkan. Hasil keputusan akan disampaikan kembali kepada pelapor secara transparan.
                    </div>
                  </td>
                </tr>
              </table>

              <!-- Complaint Summary Table -->
              <table width="100%" cellpadding="8" cellspacing="0" style="background-color: #f8fafc; border-radius: 8px; font-size: 13px; margin-bottom: 24px; border: 1px solid #e2e8f0;">
                <tr>
                  <td style="width: 35%; color: #64748b; border-bottom: 1px solid #e2e8f0;">Kategori:</td>
                  <td style="font-weight: 600; color: #0f172a; border-bottom: 1px solid #e2e8f0;">{{ ucfirst($complaint->kategori) }}</td>
                </tr>
                <tr>
                  <td style="color: #64748b; border-bottom: 1px solid #e2e8f0;">Perihal:</td>
                  <td style="font-weight: 600; color: #0f172a; border-bottom: 1px solid #e2e8f0;">{{ $complaint->judul }}</td>
                </tr>
                <tr>
                  <td style="color: #64748b; border-bottom: 1px solid #e2e8f0;">Waktu Lapor:</td>
                  <td style="font-weight: 600; color: #0f172a; border-bottom: 1px solid #e2e8f0;">{{ $complaint->created_at->format('d/m/Y H:i') }} WIB</td>
                </tr>
                @if($complaint->nomor_rekening)
                <tr>
                  <td style="color: #64748b;">Nomor Rekening:</td>
                  <td style="font-weight: 600; color: #0f172a;">{{ $complaint->nomor_rekening }}</td>
                </tr>
                @endif
              </table>

              <!-- CTA Button to Track -->
              <table width="100%" cellpadding="0" cellspacing="0" style="margin: 28px 0;">
                <tr>
                  <td align="center">
                    <a href="{{ url('/#cek-pengaduan') }}?code={{ $complaint->ticket_code }}" style="background-color: #0066CC; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-size: 13px; font-weight: 700; display: inline-block; letter-spacing: 0.5px;">
                      Lacak Status Pengaduan Saya &rarr;
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Anti-Fraud Security Ribbon -->
              <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; margin-top: 24px;">
                <tr>
                  <td style="padding: 16px;">
                    <strong style="color: #991b1b; font-size: 12px; display: block; margin-bottom: 4px; text-transform: uppercase;">
                      🛡️ Peringatan Keamanan & Anti-Fraud
                    </strong>
                    <p style="color: #7f1d1d; font-size: 11px; margin: 0; line-height: 1.5;">
                      Perumda BPR Majalengka <strong>TIDAK PERNAH memungut biaya apapun</strong> dalam proses penanganan pengaduan nasabah. Petugas kami juga <strong>TIDAK PERNAH meminta</strong> nomor PIN ATM, kode OTP SMS, kata sandi, ataupun meminta transfer ke rekening atas nama pribadi. Waspada penipuan!
                    </p>
                  </td>
                </tr>
              </table>

            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color: #0f172a; padding: 24px 32px; text-align: center; color: #94a3b8; font-size: 12px; line-height: 1.6;">
              <strong style="color: #ffffff;">PERUMDA BPR MAJALENGKA</strong><br>
              Jl. Raya K.H. Abdul Halim No. 338, Majalengka Wetan, Jawa Barat 45411<br>
              Call Center: (0233) 281144 | WhatsApp CS: 0811-1111-6526<br>
              <span style="font-size: 11px; color: #64748b; margin-top: 8px; display: inline-block;">
                Berizin dan Diawasi oleh Otoritas Jasa Keuangan (OJK) &bull; Dijamin Lembaga Penjamin Simpanan (LPS)
              </span>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>
