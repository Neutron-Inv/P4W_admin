<?php


namespace App\Filters\App\JobPost;

use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class LocationFilter extends FilterBuilder
{
    public function search($value = null)
    {
        $this->builder->where(function (Builder $builder) use ($value) {
            $builder->whereRaw("address LIKE ?", "%{$value}%");
        });
    }
}
