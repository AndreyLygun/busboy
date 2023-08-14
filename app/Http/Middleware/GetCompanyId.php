<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class GetCompanyId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('company_id')) {
            $user_id = auth()->user()?->id;
            if ($user_id) {
                $company = Company::all()->where('user_id', $user_id)->first();
                session(['company_id'=>$company->id]);
            } else {
                session(['company_id'=>Route::current()->parameter('id')]);
            }
        }
        return $next($request);
    }
}
