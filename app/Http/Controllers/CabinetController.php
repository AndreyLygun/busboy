<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Dish;
use App\Models\Company;

class CabinetController extends Controller
{
    public function getSettings() {
        $company = Company::find(auth()->user()->company_id);
        return view('cabinet.settings', compact('company'));
    }

    public function saveSettings() {
        $company = Company::find(auth()->user()->company_id);
        $attr=request()->validate([
            'name'=>'',
            'officialname'=>'',
            'hasDelivery'=>'boolean',
            'hasCardPayment'=>'boolean',
            'description'=>'',
            'image' => ''
        ]);
        if ($attr["image_clear"]) {
            $attr["image"] = '';
        } elseif ($file = request()->file('image')) {
            $filename = 'logo.' . $file->extension();
            $dir = auth()->user()->company_id;
            $file->storeAs($dir, $filename, 'customers');
            $attr['photo'] = $filename;
        }
        $company->update($attr);
        dd($attr);
        return back()->with('message', 'Информация сохранена');
    }

    public function menu() {
        $company_id = auth()->user()->company_id;
        $menu = Dish::with('dishes')->whereCategoryId(0)->whereCompanyId($company_id)->orderBy('menu_index')->get();
        return view('cabinet.menu', compact('menu', 'company_id'));
    }
    public function places() {
        return view('cabinet.places');
    }
    public function staff() {
        return view('cabinet.staff');
    }
}
