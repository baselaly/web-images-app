<?php

namespace App\QueryFilters\Post;

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
                $query->where('body', 'LIKE', '%' . $keyword . '%');
            });
        }

        return $builder;
    }
}
