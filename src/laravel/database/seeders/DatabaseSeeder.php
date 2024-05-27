<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Currency;
use App\Models\Exchanges;
use App\Models\Wallet;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // Create roles and permissions
         $traderRole = Role::create(['name' => 'trader']);
         Permission::create(['name' => 'create_request']);
         Permission::create(['name' => 'apply_request']);
         $traderRole->givePermissionTo(['create_request', 'apply_request']);
 
         // Create users
         $users = [
             ['name' => 'User 1', 'email' => '1@mail.com', 'password' => Hash::make('12345678')],
             ['name' => 'User 2', 'email' => '2@mail.com', 'password' => Hash::make('12345678')],
             ['name' => 'User 3', 'email' => '3@mail.com', 'password' => Hash::make('12345678')],
         ];
         foreach ($users as $user) {
             $newUser = User::create($user);
             $newUser->assignRole($traderRole);
         }
 
         // Create currencies and wallets
         $currencies = ['UAH', 'USD', 'EUR'];
         foreach ($currencies as $currencyName) {
             $currency = Currency::create(['symb' => $currencyName]);
             foreach ($users as $user) {
                 $wallet = new Wallet(['balance' => 500]);
                 $wallet->user_id = User::where('email', $user['email'])->first()->id;
                 $wallet->currency_id = $currency->id;
                 $wallet->save();
             }
         }
 
         // Create exchange request
         $user1 = User::where('email', '1@mail.com')->first();
         $exchangeRequest = new Exchanges([
             'user_id' => $user1->id,
             'currency_from_id' => Currency::where('symb', 'UAH')->first()->id,
             'currency_to_id' => Currency::where('symb', 'USD')->first()->id,
             'amount_from' => 200,
             'amount_to' => 5,
             'commission' => 4,
             'availability' => true,
         ]);
         $exchangeRequest->save();
    }
}
