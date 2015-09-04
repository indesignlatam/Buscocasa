<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Listing;
use DB;

class NullFeaturedTypeFromExpiredListing extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listings:null_featured_expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Null featured_type column from expired featured listings.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        //
        $listings = Listing::whereRaw('{fn TIMESTAMPDIFF( HOUR, featured_expires_at, now() )} > 1')
                           ->get();

        $this->info('Listings to nullify featured_type: '.count($listings));
        $this->output->progressStart(count($listings));//Only for laravel 5.1

        $ids = [];
        foreach ($listings as $listing) {
            $ids[] = $listing->id;
            //$listing->featured_type = null;
            $this->output->progressAdvance();//Only for laravel 5.1
        }
        DB::table('listings')
          ->whereIn('id', $ids)
          ->update(['featured_expires_at' => null, 
                    'featured_type' => null,
                    ]);

        $this->output->progressFinish(); //Only for laravel 5.1
        $this->info('Listings succesfuly nullified');
    }
}
