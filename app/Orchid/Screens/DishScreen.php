<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Models\Dish;
use http\Client\Response;
use Illuminate\Support\Facades\Redirect;
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
            $dish = Dish::with('category')->findOrFail(request('id'));
        } elseif (request()->has('category')) {
            $dish = new Dish();
            $dish->category_id = request('category');
        } else {
            return Response::denyWithStatus(404);
        }
//        dd($dish);
        return ['dish' => $dish];
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
//        dd(request('dish'));
        $validated = request()->validate([
            'dish.id'=>'',
            'dish.name'=>'required',
            'dish.category_id' => '',
            'dish.shortname'=>'',
            'dish.description'=>'',
            'dish.hide'=>'numeric',
            'dish.article'=>'',
            'dish.photo'=>'',
//            'options'=>'',
            'dish.price'=>'numeric',
            'dish.out_price'=>'numeric',
            'dish.change_price'=>'',
            'dish.hall'=>'boolean',
            'dish.pickup'=>'boolean',
            'dish.delivery'=>'boolean',
            'dish.size'=>'',
            'dish.kbju'=>'',
            'dish.recomendation'=>'',
            'dish.timing'=>'numeric',
            'dish.special'=>'boolean',
        ]);
        Dish::updateOrCreate(['id' => $validated['dish']['id']], $validated['dish']);
        return Redirect::back();
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */

    public function layout(): iterable
    {
//        dd(Category::all());
//        dd($this->query('dish'));
//        dd(Category::where('book_id', $this->dish['category']['book_id']));
        return [
            Layout::rows([
                Input::make('dish.id')->hidden()->withoutFormType(),
                Input::make('dish.name')->title(Dish::FIELDS['name'])->horizontal()->required()->help("Как оно отображается в меню"),
                Input::make('dish.shortname')->title(Dish::FIELDS['shortname'])->horizontal()->help("Как его вядят сотрудники. Если пусто, используется название для меню"),
                Select::make('dish.category_id')
                    ->options(['36'=>3636, '12'=>1212, '13'=>1313, '14'=>1414])
//                    ->fromModel(Category::all(), 'name', 'id')
                    ->title('Категория')
                    ->horizontal(),
                TextArea::make('dish.description')->title(Dish::FIELDS['description'])->horizontal()->rows(5)->hr(),
                Input::make('dish.price')->title(Dish::FIELDS['price'])->horizontal(),
                Input::make('dish.change_price')->title(Dish::FIELDS['change_price'])->horizontal(),
                Matrix::make('dish.options')
                    ->columns([
                        'Название' => 'name',
                        'Коррекция цены' => 'change'
                    ])->title('Ценовые опции')->hr()->horizontal()
                    ->help('В колонке "Коррекция цены" укажите со знаком +/- изменение цены по сравнению с базовой ценой'),
                CheckBox::make('dish.hide')->title(Dish::FIELDS['hide'])->horizontal(),
                Group::make([
                    CheckBox::make('dish.hall')->title(Dish::FIELDS['hall'])->sendTrueOrFalse(),
                    CheckBox::make('dish.delivery')->title(Dish::FIELDS['delivery'])->sendTrueOrFalse(),
                    CheckBox::make('dish.pickup')->title(Dish::FIELDS['pickup'])->sendTrueOrFalse(),
                ]),
                Input::make('dish.size')->title(Dish::FIELDS['size'])->horizontal()->help('Размер порции (включая единицы измерения)'),
                Input::make('dish.kbju')->title(Dish::FIELDS['kbju'])->horizontal()->help('В произвольном формате'),
            ])
        ];
    }
}
