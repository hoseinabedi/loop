<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Product;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ImportData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from csv file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileContents = file('products.csv');
        $errors = array();
        for($i = 0; $i < count($fileContents); $i++){
            $data = str_getcsv($fileContents[$i]);
            try{
                Product::create([
                    'name' => $data[1],
                    'price' => $data[2],
                ]);
            }catch(\Exception $e){
                array_push($errors, $e->getMessage());
                continue;
            }
        }
        echo count($fileContents)-count($errors) . " records imported successfully in products table!";
        if (count($errors) > 0){
            echo "\n".count($errors) . " records not imported in products table! check the errors below:\n";
            print_r($errors);
        }

        $fileContents = file('customers.csv');
        $errors = array();
        $EmailPattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
        for($i = 0; $i < count($fileContents); $i++){
            $data = str_getcsv($fileContents[$i]);
            try{
                if(!preg_match($EmailPattern, $data[2])) throw new \Exception("Invalid email: " . $data[2]);
                Customer::create([
                    'job_title' => $data[1],
                    'email' => $data[2],
                    'fullname' => $data[3],
                    'registered_at' => date("Y-m-d", strtotime($data[4])),
                    'phone' => $data[5],
                ]);
            }catch(\Exception $e){
                array_push($errors, $e->getMessage());
                continue;
            }
        }
        echo count($fileContents)-count($errors) . " records imported successfully in customers table!";
        if (count($errors) > 0){
            echo "\n".count($errors) . " records not imported in customers table! check the errors below:\n";
            print_r($errors);
        }
        
        echo "\n"."Operations completed successfully!";
        return 0;
    }
}
