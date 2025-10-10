<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Promotion;

class CheckTimezones extends Command
{
    protected $signature = 'check:timezones';
    protected $description = 'Check timezone differences and promotions';

    public function handle()
    {
        // 1. MySQL times
        $timeInfo = DB::select("SELECT @@global.time_zone, @@session.time_zone, NOW() as mysql_now, UTC_TIMESTAMP() as mysql_utc")[0];
        
        $this->info("=== DATABASE TIMES ===");
        $this->line("MySQL Timezone: " . $timeInfo->{"@@global.time_zone"});
        $this->line("MySQL NOW(): " . $timeInfo->mysql_now);
        $this->line("MySQL UTC: " . $timeInfo->mysql_utc);

        // 2. Laravel times
        $this->info("\n=== LARAVEL TIMES ===");
        $this->line("Laravel Timezone: " . config('app.timezone'));
        $this->line("Laravel now(): " . now()->format('Y-m-d H:i:s'));
        $this->line("Paraguay time: " . now('America/Asuncion')->format('Y-m-d H:i:s'));

        // 3. Check difference
        $mysqlTime = Carbon::parse($timeInfo->mysql_now);
        $paraguayTime = now('America/Asuncion');
        $diffHours = $mysqlTime->diffInHours($paraguayTime);
        
        $this->info("\n=== DIFFERENCE ===");
        $this->line("Difference: " . $diffHours . " hours");
        $this->line("MySQL is " . ($mysqlTime->gt($paraguayTime) ? 'AHEAD' : 'BEHIND'));

        // 4. Check promotions
        $this->info("\n=== PROMOTIONS ===");
        $promotions = Promotion::with('product')->get();
        
        foreach ($promotions as $promo) {
            $this->line("Promo ID: " . $promo->id . " - " . ($promo->product->name ?? 'N/A'));
            $this->line("  Start: " . $promo->start_date);
            $this->line("  End: " . $promo->end_date);
            $this->line("  Active: " . ($promo->is_currently_active ? 'YES' : 'NO'));
            $this->line("---");
        }

        return 0;
    }
}