<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Models\Book;


use App\Models\Dish;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MenuExport;
use Orchid\Support\Facades\Toast;

class BookScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        $book_id = request('id')??Book::first()->id;
        $d = Category::with('Dishes')->where('book_id', $book_id)->orderBy('order')->get()->toArray();
        return ['categories' => $d];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Меню';
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('admin.menu', ['menu' => $this->query()]),

        ];
    }



    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Редактировать')->route('platform.editbook', ['id' => request('id')]),
            DropDown::make('Excel')->icon('options-vertical')->list([
                    Link::make('Импорт из Excel')->route('exportmenu'),
                    Link::make('Экспорт в Excel')->route('exportmenu'),
                ]
            ),
            Button::make('Удалить')->confirm("Вы уверены, что хотите удалить меню?")->method('deleteBook', ['id' => request('id')]),
            Button::make('Добавить категорию')
        ];
    }

    public function deleteDish($id) {
        Dish::findOrFail($id)->delete();
    }

    public function deleteBook($id) {
        $b = Book::findOrFail($id)->withCount('categories');
        dd($b->categories_count);
        $b->delete();
        Toast::success('Меню удалено');
    }
}
