<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classes;
use App\Models\Item;
use App\Models\Section;
use App\Models\Period;
use App\Models\PriceLevel;
use App\Models\Quality;
use App\Models\Station;
use App\Models\ItemSize;
use App\Models\ItemPackage;
use Illuminate\Support\Str;
use App\GlobalConstants;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user_id = User::insertGetId([
            'name' => 'Admin',
            'role' => 'super_admin',
            'phone_no' =>'45454545',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'created_at' => date('Y-m-d h:i:s')
        ]);
        Period::insert([
            'user_id' => $user_id,
            'start_date' => now(),
            'end_date' => now(),
            'status' => '1',
        ]);

        Classes::factory()->create();
        $default_classes=['Wine','Beer','Champagne','Kegs','Coolers','Miscellaneous'];
        $default_classes_types=['Liquor','Beer','Liquor','Liquor','Beer','Miscellaneous'];
        
        for($i=0;$i<count($default_classes);$i++){
            Classes::factory()->create([
                'name' => $default_classes[$i],
                'type' => $default_classes_types[$i]
            ]);
        }
       
        $liquor_categories=['Default','Pre-Mixes','Cognac','Tequila','Irish Whiskey','Scotch Whiskey','Apertif','Canadian Whiskey',
        'Bourbon','Rye','Rum','Brandy','Port/Sherry','Vodka','Gin','Liqueurs','Vermouth','Whiskey','Bitters','Schnapps',
        'Mixers','Cordials','House Rum','House Tequila','House Gin','Soju','Testing Category'];
        for($i=0;$i<count($liquor_categories);$i++){
            Category::factory()->create([
                'name' => $liquor_categories[$i]
            ]);
        }

        $wine_categories=['Default','Wine','Red','White','Rose','Siena','Shiraz','Syrah','Red Zinfandel','White Zinfandel',
        'Event Wine','Port & Sherry','Champagne','Sauvignon Blanc','Sake','Rubicon','Domestic','Premium','Cabernet Sauvignon',
        'Chardonnay','Fume Blanc','Gerwuztraminer','Riesling','Pinot Noir','Pinot Gris','Pinot Grigio','Merlot','Sparkling',
        'House White Wine','House Red Wine'];
        for($i=0;$i<count($wine_categories);$i++){
            Category::factory()->create([
                'name' => $wine_categories[$i],
                'class_id' => 2
            ]);
        }
        $beer_categories=['Default','Beer','Canned Beer','Imported Beer','Domestic Beer','Bottle Beer'];
        for($i=0;$i<count($beer_categories);$i++){
            Category::factory()->create([
                'name' => $beer_categories[$i],
                'class_id' => 3
            ]);
        }
        $champagne_categories=['Default','Champagne'];
        for($i=0;$i<count($champagne_categories);$i++){
            Category::factory()->create([
                'name' => $champagne_categories[$i],
                'class_id' => 4
            ]);
        }
        $kegs_categories=['Default','Kegs','Imported Kegs','Domestic Kegs','Brewed Inhouse'];
        for($i=0;$i<count($kegs_categories);$i++){
            Category::factory()->create([
                'name' => $kegs_categories[$i],
                'class_id' => 5
            ]);
        }
        $coolers_categories=['Default','Coolers'];
        for($i=0;$i<count($coolers_categories);$i++){
            Category::factory()->create([
                'name' => $coolers_categories[$i],
                'class_id' => 6
            ]);
        }
        $miscellaneous_categories=['Default','Beer','Energy Drinks','Coffee','Cover Charge','Packaged Shots','Juice',
        'Water','Tea','Soda','Merchandise','Cigarettes','Cigars','Mixer','Ripe Juices','Others','Milk'];
        for($i=0;$i<count($miscellaneous_categories);$i++){
            Category::factory()->create([
                'name' => $miscellaneous_categories[$i],
                'class_id' => 7
            ]);
        }
       
        $liquor_qualities=['Default','UNKNOWN','Merge','Well','Call','Premium','Top Shelf',
        'Super Premium','Speciality','Deluxe','Exotic'];
        for($i=0;$i<count($liquor_qualities);$i++){
            Quality::factory()->create([
                'name' => $liquor_qualities[$i]
            ]);
        }

        $wine_qualities=['Default','UNKNOWN','Merge','Sake','Rose','Red Zinfandel','Riesling',
        'Box','Keg','Pinot Noir','Pinot Grigio','Sangria','White Zinfandel','Ports & Sherry',
        'Champagne','Merlot','Chardonnay','Wine','Domestic','Imported','Red Wine','White Wine','Banquet Wine',
        'Cabernet Sauvignon','Fume Blanc','Alsace','Bordeaux','Burgundy','Beaujolais','Cotes du Rhone','Jura',
        'Languedoc','Loire Valley','Medoc','Provence','France','Italy','Spain','United States','Argentina',
        'Australia','Germany','South Africa','Chile','Portugal','RWOM','WWOM'];
        for($i=0;$i<count($wine_qualities);$i++){
            Quality::factory()->create([
                'name' => $wine_qualities[$i],
                'class_id' => 2
            ]);
        }

        $beer_qualities=['Default','UNKNOWN','Merge','Domestic','Imported','Craft','Canned',
        'Bottled','Premium','Local','Ale'];
        for($i=0;$i<count($beer_qualities);$i++){
            Quality::factory()->create([
                'name' => $beer_qualities[$i],
                'class_id' => 3
            ]);
        }
        $champagne_qualities=['Default','UNKNOWN','Merge','Imported','Domestic'];
        for($i=0;$i<count($champagne_qualities);$i++){
            Quality::factory()->create([
                'name' => $champagne_qualities[$i],
                'class_id' => 4
            ]);
        }
        $kegs_qualities=['Default','UNKNOWN','Merge','Imported','Domestic','Beer Flights'];
        for($i=0;$i<count($kegs_qualities);$i++){
            Quality::factory()->create([
                'name' => $kegs_qualities[$i],
                'class_id' => 5
            ]);
        }
        $coolers_qualities=['Default','Merge','UNKNOWN'];
        for($i=0;$i<count($coolers_qualities);$i++){
            Quality::factory()->create([
                'name' => $coolers_qualities[$i],
                'class_id' => 6
            ]);
        }
        $miscellaneous_qualities=['Default','UNKNOWN','Merge'];
        
        for($i=0;$i<count($miscellaneous_qualities);$i++){
            Quality::factory()->create([
                'name' => $miscellaneous_qualities[$i],
                'class_id' => 7
            ]);
        }

        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
    }
}
