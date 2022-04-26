<?php

namespace App\Visitable;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PendingVisit
{
    protected $model;

    protected $attributes = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function withIp($ip = null)
    {
        $this->attributes['ip'] = $ip ?? request()->ip();
        return $this;
    }

    public function withData($data)
    {
        $this->attributes = array_merge($this->attributes, $data);
        return $this;
    }

    public function withUser(?User $user = null)
    {
        $this->attributes['user_id'] = $user->id ?? auth()->id();
        return $this;
    }

    protected function buildJsonColumns()
    {
        return collect($this->attributes)
            ->mapWithKeys(function ($value, $index) {
                return [
                    'data->' . $index => $value
                ];
            })->toArray();
    }

    public function __destruct()
    {
        $this->model->visits()->firstOrCreate($this->buildJsonColumns(), [
            'data' => $this->attributes
        ]);
    }
}