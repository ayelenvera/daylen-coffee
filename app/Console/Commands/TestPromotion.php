<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Promotion;
use App\Models\Product;

class TestPromotion extends Command
{
    protected $signature = 'test:promotion';
    protected $description = 'Test promotion timezone issues';

    public function handle()
    {
        $this->info("=== TESTING PROMOTION TIMEZONES ===");
        
        // 1. Current times
        $this->info("\n--- CURRENT TIMES ---");
        $mysqlTime = DB::select("SELECT NOW() as now")[0]->now;
        $laravelTime = now('America/Asuncion')->format('Y-m-d H:i:s');
        $this->line("MySQL: " . $mysqlTime);
        $this->line("Laravel: " . $laravelTime);
        $this->line("Real time Paraguay: ~" . date('H:i'));

        // 2. Create test promotion
        $this->info("\n--- CREATING TEST PROMOTION ---");
        $product = Product::first();
        
        if (!$product) {
            $this->error("No products found!");
            return 1;
        }

        $now = Carbon::now('America/Asuncion');
        $start = $now->copy()->addMinutes(2); // Starts in 2 minutes
        $end = $now->copy()->addMinutes(62);  // Ends in 1 hour 2 minutes

        $this->line("Product: " . $product->name);
        $this->line("Test promotion times:");
        $this->line("  Start: " . $start->format('H:i'));
        $this->line("  End: " . $end->format('H:i'));
        $this->line("  Current: " . $now->format('H:i'));

        // 3. Create promotion WITHOUT compensation
        $promo = new Promotion();
        $promo->product_id = $product->id;
        $promo->discount_percentage = 25;
        $promo->original_price = $product->price;
        $promo->start_date = $start;
        $promo->end_date = $end;
        $promo->is_active = true;
        $promo->save();

        $this->info("Promotion created! ID: " . $promo->id);

        // 4. Check promotion status
        $this->info("\n--- CHECKING PROMOTION STATUS ---");
        $savedPromo = Promotion::find($promo->id);
        
        $this->line("Saved in DB:");
        $this->line("  Start: " . $savedPromo->start_date);
        $this->line("  End: " . $savedPromo->end_date);
        $this->line("  Is Currently Active: " . ($savedPromo->is_currently_active ? 'YES' : 'NO'));
        
        // 5. Manual check
        $this->info("\n--- MANUAL CHECK ---");
        $currentTime = Carbon::now('America/Asuncion');
        $this->line("Current time: " . $currentTime->format('Y-m-d H:i:s'));
        $this->line("Start time: " . $savedPromo->start_date->format('Y-m-d H:i:s'));
        $this->line("End time: " . $savedPromo->end_date->format('Y-m-d H:i:s'));
        
        $shouldBeActive = $savedPromo->is_active && 
                         $savedPromo->start_date->lte($currentTime) && 
                         $savedPromo->end_date->gte($currentTime);
        
        $this->line("Should be active: " . ($shouldBeActive ? 'YES' : 'NO'));

        // 6. Clean up
        $savedPromo->delete();
        $this->info("\nTest promotion deleted.");

        return 0;
    }
}