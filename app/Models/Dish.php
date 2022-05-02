<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dish extends Model
{
    use HasFactory;
    public const fields = [
        "menu_index" => "Порядковый номер",
        "fullname" =>"Название",
        "shortname" => "Короткое название",
        "category_id" => "Категория",
        "description" => "Описание",
        "alias" => "Псеводним",
        "hide" => "Стоп-лист",
        "article" => "Артикул",
        "photo" => "Фотография",
        "options" => "Опции",
        "price" => "Цена в зале",
        "out_price" => "Цена на вынос/доставку",
        "change_price" => "Временная коррекция цены",
        "hall" => "Доступно в зале",
        "pickup" => "Доступно для самовывоза",
        "delivery" => "Доступно для доставки",
        "size" => "Вес/объём",
        "kbju" => "КБЖУ",
        "recomendation" => "Рекомендованные блюда",
        "timing" => "Время приготовления",
        "special" => "Спецпредложение"
    ];
    protected $guarded=['id'];
    public function category() {
        return $this->belongsTo(Dish::class);
    }
    public function dishes() {
        return $this->hasMany(Dish::class, 'category_id')->orderBy('menu_index');
    }

    public function options() {
        // Возвращает массив ценовых модификаторов
        $txt = $this->options;
        if ($txt == '') return [];
        $output = [];
        $txt = str_replace(["\r\n", "\n\r"], "\n", $txt);
        $sections = explode("\n\n", $txt); //Делим на разделы, разделённые пустой строкой
        foreach ($sections as $i => $section) {
            $lines = explode("\n", $section);
            // Извлекаем метку раздела (если она есть, то начинается с #
            if ($lines[0][0] == '#') {
                $select['label'] = substr($lines[0], 1);
                unset($lines[0]);
            } else {
                $select['label'] = '';
            }
            $select['items'] = [];
            foreach ($lines as $line) {
                if ($line[0] == '(') {  //Начинается со скобки
                    $p = strpos($line, ')');     //Ищем первую закрывающую скобку
                    $deltaprice = substr($line, 1, $p - 1);
                    $name = ltrim(substr($line, $p + 1));
                } else {
                    $deltaprice = '+0';
                    $name = $line;
                }
                $select["items"][] = ["value" => $deltaprice, "name"=> $name];
            }
            $output[] = $select;
        }
        return $output;
    }

    public function comments() {
        $tablename = $this->table;
        $columnInfos =  DB::select("SHOW FULL COLUMNS FROM $tablename");
        $comments = [];
        foreach ($columnInfos as $columnInfo) {
            $comments[$columnInfo->Field] = $columnInfo->Comment;
        }
        return $comments;
    }

}

$options = [
    ["label"=>"Варианты",
    "items"=>
        ["value" => 12, "name" => "Вариант 1.1"],
        ["value" => 25, "name" => "Вариант 1.2"],
        ["value" => 12, "name" => "Вариант 1.3"]
    ],
    ["label"=>"Варианты2",
        "items"=>
        ["value" => 12, "name" => "Вариант 2.1"],
        ["value" => 25, "name" => "Вариант 2.2"],
        ["value" => 12, "name" => "Вариант 2.3"]
    ]
];
