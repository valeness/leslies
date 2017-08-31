<?php

namespace App\Console\Commands;

use App\Http\Libraries\Leslies;
use Illuminate\Console\Command;

class DataStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull data down from API and store in ElasticSearch';

    private static $url = "http://www.poolsupplyworld.com/api.cfm";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $ids = Leslies::call();

        foreach ($ids as $id) {
            if (!empty($id) && is_numeric($id)) {
                $prod = Leslies::call(['productid' => $id]);
                Leslies::indexDocument($prod);
            }
        }
    }
}
