<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MenuExport;
use App\Models\Dish;

class MenuController extends Controller
{


    public function ImportMenu() {
        //-- TODO: импорт меню
    }

    public function ExportMenu() {
        $company_id = auth()->user()->company_id;
        $menu = Dish::with('dishes')->whereCompanyId($company_id)->orderBy('menu_index')->get();
        $export = [];
        foreach($menu as $category) {
            foreach ($category->dishes as $dish) {
                $d = [];
                foreach(Dish::fields as $key=>$value) {
                    // Создаём новое блюдо с колонками другого названия
                    if ($key=='category_id') {
                        $d[$value] = $category->name;
                    } else {
                        $d[$value] = $dish[$key];
                    }
                }
                $export[] = $d;
            }
        }
        $menuExport = new MenuExport($export);
        return Excel::download($menuExport, 'invoices.xlsx');
    }

    public function addDish() {
        //-- TODO: добавляем блюдо
        $company_id = auth()->user()->company_id;
        $categories = Dish::whereCategoryId($company_id)->orderBy('menu_index')->get();
        $dish = new Dish();
        dump(old());
        return view('cabinet.editDish', compact(['dish', 'categories']));
    }

    public function editDish(Dish $dish) {
        $company_id = auth()->user()->company_id;
        if ($dish->company_id != $company_id) {
            return abort(404);
        }
        if ($dish->category_id == 0) {
            return abort(404);
        }
        $categories = Dish::whereCategoryId($company_id)->orderBy('menu_index')->get();
        return view('cabinet.editDish', compact(['dish', 'categories']));
    }

    public function updateDish(Dish $dish) {
//        dump(\request()->all());
        $attr = request()->validate([
            "fullname" =>"required",
            "shortname" => "",
            "category_id" => "",
            "description" => "",
            "hide" => "",
            "article" => "",
            "photo_clear" => "",
            "photo" => "image",
            "options" => "",
            "price" => "numeric",
            "out_price" => "numeric",
            "change_price" => "numeric",
            "pickup" => "",
            "delivery" => "",
            "size" => "",
            "kbju" => "",
            "recomendation" => "",
            "timing" => "",
            "special" => ""
        ]);

        if ($attr["photo_clear"]) {
            $attr["photo"] = '';
        } elseif ($file = request()->file('photo')) {
            $filename = str_slug($attr['fullname']) . '_' . $dish->id . '.' . $file->extension();
            $dir = auth()->user()->company_id . '/img';
            $file->storeAs($dir, $filename, 'customers');
            $attr['photo'] = $filename;
        }
        $dish->update($attr);
//        dump($attr);
        return back();
    }

    public function deleteDishes() {
        //-- TODO: удаляем блюдо
    }

    public function addCategory() {
        //-- TODO: удаляем категорию
    }


    public function changeOrder() {
        $ids = request('ids');
        $index = 1000;
        foreach ($ids as $id) {
            $item = Dish::find($id);
            if ($item->company_id != auth()->user()->company_id) {
                return ['status'=>'error', 'msg'=>"Нет доступа к элементу c id=$id"];
            }
            $item->menu_index = $index++;
            $item->save();
        }
        return ['status'=>'ok', 'msg'=>'Последовательность элементов сохранена'];
        //-- TODO: порядок блюд в меню

    }
}
