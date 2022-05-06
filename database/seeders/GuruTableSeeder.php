<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;

class GuruTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_guru = [
            [
                'kode_guru' => 'NB',
                'nama_guru' => 'Sukardi, S.Ag.'
            ],
            [
                'kode_guru' => 'NC',
                'nama_guru' => 'Luqman Hakim, S.Pd.'
            ],
            [
                'kode_guru' => 'ND',
                'nama_guru' => 'Zaenal Arifin, S.Pd.I'
            ],
            [
                'kode_guru' => 'NE',
                'nama_guru' => "Ah Zanin Nu'man, M.Pd.I"
            ],
            [
                'kode_guru' => 'NF',
                'nama_guru' => 'Arif Rohman, S.Pd.I'
            ],
        ];

        foreach ($data_guru as $guru) {
            $user = new User();
            $user->username = $guru['kode_guru'];
            $user->password = bcrypt($guru['kode_guru']);
            $user->role = "guru";
            $user->save();

            $new_guru = new Guru();
            $new_guru->kode_guru = $guru['kode_guru'];
            $new_guru->nama_guru = $guru['nama_guru'];
            $new_guru->user_id = $user->id;
            $new_guru->save();
        }
    }
}
