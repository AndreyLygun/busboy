<?php


namespace App\Busboy;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

trait Multitenant
{
    use HasEvents;
    protected static function booted()
    {
        static::addGlobalScope('company', function (Builder $builder) {
            $builder->where('company_id', '=', session('company_id'));
        });
        static::creating(function($model) {
            if ($company_id = session('company_id')) {
                $model->company_id = $company_id;
            }
        });
    }
}
