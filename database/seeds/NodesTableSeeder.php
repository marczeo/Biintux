<?php

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\FileNotFoundException;
use App\Repositories\MibiciRepository;
use App\Node;
class NodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $content = File::get('mibici_stations');

        foreach ($content as $line)
        {
   	 		//echo htmlspecialchars($line) ."<br />\n";
		}
    }
}
