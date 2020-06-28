<?php

namespace App\QueryFilters\Post;

use Closure;
use Illuminate\Http\Request;

class UserIdsFilter
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

        if (array_key_exists('user_ids', $this->filters)) {
            $builder->whereIn('user_id', $this->filters['user_ids']);
        }

        return $builder;
    }
}
