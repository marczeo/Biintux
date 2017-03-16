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
        //$contents = File::get(storage_path('\mibici_stations'));

        if ($contents = fopen("mibici_stations", "r")) 
        {
	    	while(!feof($contents))
	    	{
		        $line = fgets($contents);
		        $fragments = explode(", ", $line);

	            $newNode= new Node;
	            $newNode->latitude=$fragments[0];
	            $newNode->longitude=$fragments[1];
	        	$newNode->description=$fragments[2];
	        	$newNode->type="mibici";
	        	$newNode->save();
		        //dd($line);
	    	}

	    	fclose($contents);
		}

    }
}
