<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Models\Dish;
use http\Client\Response;
use Orchid\Screen\Actions\Button;

use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;



class DishScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public $dish;

    public function query(): iterable
    {
        if (request()->has('id')) {
            $this->dish = Dish::with('category')->findOrFail(request('id'));
        } elseif (request()->has('category')) {
            $this->dish = new Dish();
            $this->dish->category_id = request('category');
        } else {
            return Response::denyWithStatus(404);
        }
        return [$this->dish];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return request()->has('id')?'Редактирование блюда':'Новое блюдо';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Сохранить')->method('Save')
        ];
    }

    public function Save() {
        $validated = request('dish')->validate([
            'dish.id'=>'',
            'name'=>'required',
            'category_id' => '',
            'shortname'=>'',
            'description'=>'',
            'hide'=>'numeric',
            'article'=>'',
            'photo'=>'',
//            'options'=>'',
            'price'=>'numeric',
            'out_price'=>'numeric',
            'change_price'=>'',
            'hall'=>'boolean',
            'pickup'=>'boolean',
            'delivery'=>'boolean',
            'size'=>'',
            'kbju'=>'',
            'recomendation'=>'',
            'timing'=>'numeric',
            'special'=>'boolean',
        ]);
        $id = $validated['id'];
        if ($id) {
            $dish = Dish::findOrFail($id);
            $dish->update($validated);
        } else {
            $dish = Dish::create($validated);
            $dish->save();
        }
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        dd($this->dish);
        return [
            Layout::rows([
                Input::make('id')->hidden()->withoutFormType(),
                Input::make('name')->title(Dish::FIELDS['name'])->horizontal()->required()->help("Как оно отображается в меню"),
                Input::make('shortname')->title(Dish::FIELDS['shortname'])->horizontal()->help("Как его вядят сотрудники. Если пусто, используется название для меню"),
                Select::make('category_id')
                    ->fromQuery(Category::where('book_id', $this->dish['category']['book_id']), 'name', 'id')
                    ->title('Категория')
                    ->horizontal(),
                TextArea::make('description')->title(Dish::FIELDS['description'])->horizontal()->rows(5)->hr(),
                Input::make('price')->title(Dish::FIELDS['price'])->horizontal(),
                Input::make('change_price')->title(Dish::FIELDS['change_price'])->horizontal(),
                Matrix::make('options')
                    ->columns([
                        'Название' => 'name',
                        'Коррекция цены' => 'change'
                    ])->title('Ценовые опции')->hr()->horizontal()
                    ->help('В колонке "Коррекция цены" укажите со знаком +/- изменение цены по сравнению с базовой ценой'),
                CheckBox::make('hide')->title(Dish::FIELDS['hide'])->horizontal(),
                Group::make([
                    CheckBox::make('hall')->title(Dish::FIELDS['hall'])->sendTrueOrFalse(),
                    CheckBox::make('delivery')->title(Dish::FIELDS['delivery'])->sendTrueOrFalse(),
                    CheckBox::make('pickup')->title(Dish::FIELDS['pickup'])->sendTrueOrFalse(),
                ]),
                Input::make('size')->title(Dish::FIELDS['size'])->horizontal()->help('Размер порции (включая единицы измерения)'),
                Input::make('kbju')->title(Dish::FIELDS['kbju'])->horizontal()->help('В произвольном формате'),
            ])
        ];
    }
}
