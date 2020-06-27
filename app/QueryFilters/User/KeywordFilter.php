<?php

namespace App\QueryFilters\User;

use Closure;
use Illuminate\Http\Request;

class KeywordFilter
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

        if (array_key_exists('keyword', $this->filters)) {
            $keyword = $this->filters['keyword'];
            $builder->where(function ($query) use ($keyword) {
                $query->where('first_name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('last_name', 'Like', '%' . $keyword . '%')
                    ->orWhere('bio', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('email', 'LIKE', '%' . $keyword . '%');
            });
        }

        return $builder;
    }
}
