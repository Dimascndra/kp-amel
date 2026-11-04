<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembaruan Status Pengaduan - Bank Majalengka</title>
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
                      Pembaruan Status Pengaduan
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
                Kami menginformasikan bahwa laporan pengaduan Anda dengan rincian berikut telah mengalami pembaruan status:
              </p>

              <!-- Status Badge Card -->
              <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 12px; margin: 20px 0;">
                <tr>
                  <td style="padding: 20px; text-align: center;">
                    <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 6px;">
                      KODE UNIK PENGADUAN
                    </span>
                    <span style="font-family: 'JetBrains Mono', Consolas, monospace; font-size: 20px; font-weight: 800; color: #002D62; letter-spacing: 2px; display: inline-block;">
                      {{ $complaint->ticket_code }}
                    </span>
                    <div style="margin-top: 12px;">
                      @if($complaint->status === 'selesai')
                        <span style="background-color: #d1fae5; color: #065f46; font-size: 13px; font-weight: 800; padding: 6px 16px; border-radius: 20px; display: inline-block; text-transform: uppercase;">
                          ✓ STATUS: SELESAI
                        </span>
                      @elseif($complaint->status === 'ditindaklanjuti')
                        <span style="background-color: #dbeafe; color: #1e40af; font-size: 13px; font-weight: 800; padding: 6px 16px; border-radius: 20px; display: inline-block; text-transform: uppercase;">
                          ⚙ STATUS: DITINDAKLANJUTI
                        </span>
                      @else
                        <span style="background-color: #fef3c7; color: #92400e; font-size: 13px; font-weight: 800; padding: 6px 16px; border-radius: 20px; display: inline-block; text-transform: uppercase;">
                          ⏳ STATUS: DIPROSES
                        </span>
                      @endif
                    </div>
                  </td>
                </tr>
              </table>

              @if($complaint->catatan_petugas)
              <!-- Official Officer Response -->
              <div style="background-color: #f0fdf4; border-left: 4px solid #10b981; padding: 16px; border-radius: 8px; margin: 20px 0;">
                <strong style="color: #065f46; font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 4px;">
                  Tanggapan Resmi Tim Bank Majalengka:
                </strong>
                <p style="color: #1e293b; font-size: 13px; margin: 0; white-space: pre-line;">
                  {{ $complaint->catatan_petugas }}
                </p>
              </div>
              @endif

              <!-- CTA Button to Track -->
              <table width="100%" cellpadding="0" cellspacing="0" style="margin: 28px 0;">
                <tr>
                  <td align="center">
                    <a href="{{ url('/#cek-pengaduan') }}?code={{ $complaint->ticket_code }}" style="background-color: #0066CC; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-size: 13px; font-weight: 700; display: inline-block;">
                      Lihat Rincian Pengaduan di Website &rarr;
                    </a>
                  </td>
                </tr>
              </table>

            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color: #0f172a; padding: 24px 32px; text-align: center; color: #94a3b8; font-size: 12px; line-height: 1.6;">
              <strong style="color: #ffffff;">PERUMDA BPR MAJALENGKA</strong><br>
              Call Center: (0233) 281144 | WhatsApp CS: 0811-1111-6526<br>
              <span style="font-size: 11px; color: #64748b; margin-top: 8px; display: inline-block;">
                Berizin dan Diawasi oleh OJK &bull; Dijamin LPS
              </span>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>
