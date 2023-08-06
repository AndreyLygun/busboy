<?php

use App\Busboy\Multitenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishesTable extends Migration
{
    use Multitenant;
    protected $quarded = ['id', 'company_id'];

    public function up()
    {
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('company_id')->index();
            $table->foreignId('category_id')->constrained();

            $table->string('name', 255)->default('')->comment('Полное название');
            $table->string('shortname', 255)->comment('Краткое название')->nullable();
            $table->text('description')->comment('Описание')->nullable();
            $table->string('alias', 255)->comment('url')->default('');
            $table->integer('order')->comment('Порядковый номер')->default(0);
            $table->boolean('hide')->comment('Стоп-лист')->default(false);
            $table->string('article', 255)->comment('Артикул')->nullable();
            $table->string('photo', 255)->comment('Фотография')->nullable();
            $table->json('options')->comment('Опции')->nullable();
            $table->float('price', 8, 2)->comment('Цена в зале')->default(0);
            $table->float('out_price', 8, 2)->comment('Цена на вынос/доставку')->default(0);
            $table->float('change_price', 8, 2)->comment('Временное изменение цены (+/-)')->nullable();
            $table->boolean('hall')->comment('Доступно в зале')->default(true)->nullable();
            $table->boolean('pickup')->comment('Доступно для самовывоза')->default(true)->nullable();
            $table->boolean('delivery')->comment('Доступно для доставки')->default(true)->nullable();
            $table->string('size', 255)->comment('Размер порции')->default('')->nullable();
            $table->string('kbju', 255)->comment('КБЖУ')->default('')->nullable();
            $table->text('recomendation')->comment('Рекомендации')->nullable()->nullable();
            $table->integer('timing')->comment('Время на приготовление (в минутах)')->default(0)->nullable();
            $table->boolean('special')->comment('Особое предложение')->default(false)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dishes');
    }
}
