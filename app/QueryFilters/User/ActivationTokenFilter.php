<?php

namespace App\QueryFilters\User;

use Closure;
use Illuminate\Http\Request;

class ActivationTokenFilter
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**  
     * Handle the process of the Pipe  
     *  
     * @param Request $request  
     * @param Closure $next  
     * @return Builder  
     */
    public function handle($request, Closure $next)
    {
        $builder = $next($request);

        if (array_key_exists('activation_token', $this->filters)) {
            $builder->where('activation_token', $this->filters['activation_token']);
        }

        return $builder;
    }
}
