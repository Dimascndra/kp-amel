<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Kantor Pusat Operasional',
                'type' => 'Pusat',
                'address' => 'Jl. Raya K.H. Abdul Halim No. 338, Majalengka Wetan, Kec. Majalengka, Jawa Barat 45411',
                'phone' => '(0233) 281144',
                'whatsapp' => '0811-1111-6526',
                'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'is_main' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Kantor Kas Kadipaten',
                'type' => 'Kas',
                'address' => 'Jl. Raya Kadipaten No. 45, Kec. Kadipaten (Dekat Pasar Kadipaten)',
                'phone' => '(0233) 282510',
                'whatsapp' => '0811-1111-6526',
                'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'is_main' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Kantor Kas Jatiwangi',
                'type' => 'Kas',
                'address' => 'Jl. Pos No. 12, Sutawangi, Kec. Jatiwangi (Kawasan Genteng & Keramik)',
                'phone' => '(0233) 881230',
                'whatsapp' => '0811-1111-6526',
                'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'is_main' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Kantor Kas Talaga',
                'type' => 'Kas',
                'address' => 'Jl. Jenderal Ahmad Yani No. 88, Talaga Kulon, Kec. Talaga',
                'phone' => '(0233) 811090',
                'whatsapp' => '0811-1111-6526',
                'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'is_main' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Kantor Kas Cikijing',
                'type' => 'Kas',
                'address' => 'Jl. Raya Kasturi No. 15, Kec. Cikijing (Pusat Konveksi Celana Jeans)',
                'phone' => '(0233) 891340',
                'whatsapp' => '0811-1111-6526',
                'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'is_main' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'Kantor Kas Rajagaluh',
                'type' => 'Kas',
                'address' => 'Jl. Pangeran Muhamad No. 34, Kec. Rajagaluh (Sentra Anyaman Bambu)',
                'phone' => '(0233) 511450',
                'whatsapp' => '0811-1111-6526',
                'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'is_main' => false,
                'sort_order' => 6,
            ],
            [
                'name' => 'Kantor Kas Leuwimunding',
                'type' => 'Kas',
                'address' => 'Jl. Raya Mirat No. 22, Kec. Leuwimunding (Dekat Pertigaan Parapatan)',
                'phone' => '(0233) 512670',
                'whatsapp' => '0811-1111-6526',
                'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'is_main' => false,
                'sort_order' => 7,
            ],
            [
                'name' => 'Kantor Kas Sukahaji',
                'type' => 'Kas',
                'address' => 'Jl. Raya Sukahaji No. 10, Cikalong, Kec. Sukahaji',
                'phone' => '(0233) 882190',
                'whatsapp' => '0811-1111-6526',
                'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'is_main' => false,
                'sort_order' => 8,
            ],
            [
                'name' => 'Kantor Kas Maja',
                'type' => 'Kas',
                'address' => 'Jl. Pasukan Sindangkasih No. 05, Kec. Maja (Akses Wisata Panyaweuyan)',
                'phone' => '(0233) 283450',
                'whatsapp' => '0811-1111-6526',
                'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'is_main' => false,
                'sort_order' => 9,
            ],
            [
                'name' => 'Kantor Kas Kertajati',
                'type' => 'Kas',
                'address' => 'Jl. Kertajati-Kadipaten Km 3, Kec. Kertajati (Kawasan Bandara Internasional Kertajati)',
                'phone' => '(0233) 883100',
                'whatsapp' => '0811-1111-6526',
                'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'is_main' => false,
                'sort_order' => 10,
            ],
            [
                'name' => 'Kantor Kas Ligung',
                'type' => 'Kas',
                'address' => 'Jl. Raya Ligung No. 78, Kec. Ligung (Sentra Industri Pabrik)',
                'phone' => '(0233) 884520',
                'whatsapp' => '0811-1111-6526',
                'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'is_main' => false,
                'sort_order' => 11,
            ],
            [
                'name' => 'Kantor Kas Sumberjaya',
                'type' => 'Kas',
                'address' => 'Jl. Raya Cirebon-Bandung Km 28, Kec. Sumberjaya (Gerbang Perbatasan Timur)',
                'phone' => '(0233) 885670',
                'whatsapp' => '0811-1111-6526',
                'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'is_main' => false,
                'sort_order' => 12,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(
                ['name' => $branch['name']],
                $branch
            );
        }
    }
}
