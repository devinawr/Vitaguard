<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('email', 'admin@vitaguard.test')->value('id');

        $articles = [
            ['title' => 'Pentingnya Menjaga Pola Tidur', 'slug' => 'pentingnya-menjaga-pola-tidur', 'content' => 'Tidur yang cukup membantu tubuh melakukan pemulihan dan menjaga konsentrasi.', 'views' => 120, 'status' => 'published', 'published_at' => now()->subDays(7)],
            ['title' => 'Tips Menjaga Kesehatan Kulit', 'slug' => 'tips-menjaga-kesehatan-kulit', 'content' => 'Gunakan pembersih yang sesuai, pelembap, dan tabir surya secara rutin.', 'views' => 95, 'status' => 'published', 'published_at' => now()->subDays(5)],
            ['title' => 'Cara Menjaga Kesehatan Gigi', 'slug' => 'cara-menjaga-kesehatan-gigi', 'content' => 'Sikat gigi dua kali sehari, gunakan benang gigi, dan periksa rutin ke dokter gigi.', 'views' => 80, 'status' => 'published', 'published_at' => now()->subDays(3)],
            ['title' => 'Mengenal Tekanan Darah', 'slug' => 'mengenal-tekanan-darah', 'content' => 'Pemantauan tekanan darah secara berkala membantu mendeteksi risiko lebih awal.', 'views' => 0, 'status' => 'draft', 'published_at' => null],
            ['title' => 'Artikel Lama VitaGuard', 'slug' => 'artikel-lama-vitaguard', 'content' => 'Artikel ini disimpan sebagai arsip.', 'views' => 30, 'status' => 'archive', 'published_at' => now()->subMonth()],
        ];

        foreach ($articles as $article) {
            Article::create([
                'author_id' => $adminId,
                ...$article,
            ]);
        }
    }
}
