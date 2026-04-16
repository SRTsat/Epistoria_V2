<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_genre = ['Fiksi', 'Non-Fiksi', 'Novel', 'Edukasi', 'Teknologi'];

        foreach ($list_genre as $g) {
            Genre::create([
                'nama' => $g,
                'slug' => Str::slug($g)
            ]);
        }
    }
}
