<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            [ 
                'id' => 1,
                'name' => 'other',
                'description' => 'custom news',
            ],
            [ 
                'id' => 2,
                'name' => 'the guardian',
                'description' => "The Guardian is a British news organization known for independent journalism and progressive values. Founded in 1821, it covers politics, culture, and global issues with a focus on investigative reporting, social justice, and ethical transparency, reaching a global audience."
            ],
            [ 
                'id' => 3,
                'name' => 'news api',
                'description' => "News API is a service that provides access to real-time news from thousands of global sources. Users can explore and subscribe to articles based on their interests, filtering by keywords, categories, sources, and dates to stay updated on topics that matter to them."
            ],
            [ 
                'id' => 4,
                'name' => 'new york times',
                'description' => "The New York Times is a renowned American news organization founded in 1851. It delivers in-depth coverage of global and national news, politics, culture, and more, with a focus on investigative journalism and high editorial standards, reaching a worldwide audience."
            ],
        ];

        collect($sources)->each(function ($source) {
            $source['name'] = Source::normalize($source['name']);
            Source::create($source);
        });
    }
}