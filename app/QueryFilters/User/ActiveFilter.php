<?php

namespace App\QueryFilters\User;

use Closure;
use Illuminate\Http\Request;

class ActiveFilter
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

        if (array_key_exists('active', $this->filters)) {
            $builder->where('active', $this->filters['active']);
        }

        return $builder;
    }
}
