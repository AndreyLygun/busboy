<?php

namespace App\Models;

use App\Busboy\Multitenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Dish extends Model
{
    use HasFactory;
    use Multitenant;
    use AsSource;

    const FIELDS = [
        'id'=>'ID',
        'name'=>'Название',
        'shortname'=>'Краткое название',
        'description'=>'Описание',
        'hide'=>'Стоп-лист',
        'article'=>'Артикул',
        'photo'=>'Фото',
        'options'=>'Ценовые опции',
        'price'=>'Цена',
        'out_price'=>'Цена на вынос/доставку',
        'change_price'=>'Изменение цены',
        'hall'=>'Доступно в зале',
        'pickup'=>'Доступно самовывозом',
        'delivery'=>'Доступно для доставки',
        'size'=>'Размер порции',
        'kbju'=>'КБЖУ',
        'recomendation'=>'Рекомендованные блюда',
        'timing'=>'Время приготовления',
        'special'=>'Специальное предложени',
    ];

    protected $guarded=['company_id'];

    protected $casts = [
        'options' => 'array'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
