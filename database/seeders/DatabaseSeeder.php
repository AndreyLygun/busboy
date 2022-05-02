<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Imports\MenuImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Company;
use App\Models\Dish;
use App\Models\Category;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate([
            'email'=>'lygun@yandex.ru',
            'name'=>'lygun',
            'password'=>bcrypt('123'),
            'company_id'=>'demo']);

        echo "Пользователь добавлен\n";

        Company::firstOrCreate([
            'id'=>'Demo',
            'name'=>'Busboy'
        ]);

        echo "Компания добавлена\n";

        //$menu = Excel::import(new MenuImport(), 'menu.xls');
        $menuImport = new MenuImport();
        $sheets = Excel::toArray($menuImport, 'menu.xls');
        $menu_index = 0;
        foreach ($sheets[0] as $row) {
            $dish = $menuImport->makeDish($row);
            $categoryName = $dish['category_id'];
            $category = Dish::firstOrCreate(['fullname'=>$categoryName], ['menu_index'=>$menu_index++, 'category_id'=>0]);
            $dish['category_id'] = $category->id;
            $dish['company_id'] = 'demo';
            $dish['menu_index'] = $menu_index++;
            $dish = Dish::firstOrCreate(['fullname'=>$dish['fullname']], $dish);
        }

        echo "Меню создано\n";
    }
}
