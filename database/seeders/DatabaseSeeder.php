<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Imports\ExcelMenu;
use App\Models\Company;
use App\Models\Staff;
use App\Models\Desk;
use App\Models\Qr;
use App\Models\Book;
use App\Models\Category;
use App\Models\Dish;
use Maatwebsite\Excel\Facades\Excel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    protected function createRestaurant($companyId, $staff, $desks, $books) {
        session(['company_id'=>$companyId]);
        $user = User::all()->where('name', $companyId)->first();

        Company::FirstOrCreate(['id'=>$companyId], ['name'=>$companyId, 'user_id' => $user->id]);
        $phone = 79261906286;
        foreach ($staff as $name) {
            Staff::FirstOrCreate(['name'=>$name], ['phone' => $phone++, 'company_id'=>$companyId]);
        }

        foreach ($desks as $desk) {
            $desk = Desk::FirstOrCreate(['company_id' => $companyId, 'name' => $desk]);
            Qr::FirstOrCreate(['code'=>rand(0, 99999999)], ['desk_id'=>$desk->id]);
            Qr::FirstOrCreate(['code'=>rand(0, 99999999)], ['desk_id'=>$desk->id]);
            Qr::FirstOrCreate(['code'=>rand(0, 99999999)], ['desk_id'=>$desk->id]);
        }

        $dishCount = 0;
        foreach ($books as $book) {
            $menuImport = new ExcelMenu();
            $dishCount += $menuImport->import($book, 'Основное');
        }

        echo "Создано меню из $dishCount блюд\n";
    }

    public function run()
    {
        $this->createRestaurant(
            'busboy',
            ['Андрей', 'Лена', 'Наташа', 'Вася'],
            ['Первый', 'Второй', 'Третий', 'Четвёртый', 'Пятый', 'Шестой'],
            ['menu-main.admin.xls', 'menu-drinks.admin.xls']
        );
        $this->createRestaurant(
            'demo',
            ['Петя', 'Ира', 'Вика', 'Федя'],
            ['1', '2', '3', '4', '5', '6'],
            ['menu-main.demo.xls', 'menu-drinks.demo.xls']
        );
    }
}
