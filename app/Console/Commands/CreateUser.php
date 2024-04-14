<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use App\Models\User;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::create([
            'name'=>'mohamad kassem',
            'email'=>'mohamadkassem@gmail.com',
            'phone'=>'03123456',
            'password'=>bcrypt("12345678")
        ]);

        $product = Product::find(1); 
        $product->images()->create(['path' => 'path/to/image.jpg']); 
    }
}
