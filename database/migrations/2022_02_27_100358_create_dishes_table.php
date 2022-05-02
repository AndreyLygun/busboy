<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('fullname', 255)->comment('Полное название');
            $table->string('shortname', 255)->comment('Краткое название')->default('')->nullable();
            $table->integer('category_id')->comment('Категория')->default(0);
//            $table->foreignId('category_id')->references('id')->on('dishes');
            $table->string('company_id')->index('company_idx')->default('demo');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->text('description')->comment('Описание')->nullable();
            $table->string('alias', 255)->comment('url')->default('')->nullable();
            $table->integer('menu_index')->comment('Порядковый номер')->default(0)->nullable();
            $table->boolean('hide')->comment('Стоп-лист')->default(false)->nullable();
            $table->string('article', 255)->comment('Артикул')->default('')->nullable();
            $table->string('photo', 255)->comment('Фотография')->default('')->nullable();
            $table->text('options')->comment('Опции')->nullable();
            $table->float('price', 8, 2)->comment('Цена')->default(0)->nullable();
            $table->float('out_price', 8, 2)->comment('Цена на вынос/доставку')->default(0)->nullable();
            $table->float('change_price', 8, 2)->comment('Временное изменение цены (+/-)')->default(0)->nullable();
            $table->boolean('hall')->comment('В зале')->default(true)->nullable();
            $table->boolean('pickup')->comment('Самовывоз')->default(true)->nullable();
            $table->boolean('delivery')->comment('Доставка')->default(true)->nullable();
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
