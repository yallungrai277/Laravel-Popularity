<?php

namespace App\Visitable;

use App\Models\Visit;
use App\Visitable\Concerns\FiltersByPopulariyTimeFrame;
use App\Visitable\PendingVisit;
use Illuminate\Database\Eloquent\Builder;

trait Visitable
{
    use FiltersByPopulariyTimeFrame;

    public function visit()
    {
        return new PendingVisit($this);
    }

    public function visits()
    {
        return $this->morphMany(Visit::class, 'visitable');
    }

    public function scopeWithTotalVisitCount(Builder $query)
    {
        $query->withCount('visits as visit_count_total');
    }
}