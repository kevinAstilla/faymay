<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsStrategyService;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch articles using the news strategy service';

    /**
     * Execute the console command.
     */
    public function handle(NewsStrategyService $newsStrategyService)
    {
        $newsStrategyService->fetchArticles();
        $this->info('News articles fetched and saved successfully.');
    }
}
