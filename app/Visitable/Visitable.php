<?php

namespace App\Visitable;

use App\Models\Visit;
use App\Visitable\PendingVisit;

trait Visitable
{
    public function visit()
    {
        return new PendingVisit($this);
    }

    public function visits()
    {
        return $this->morphMany(Visit::class, 'visitable');
    }
}