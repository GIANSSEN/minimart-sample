<?php

namespace App\ValueObjects;

use Carbon\Carbon;

class ActivityItem
{
    public string $type;
    public string $description;
    public Carbon $created_at;
    public string $icon;
    public string $color;

    public function __construct(
        string $type,
        string $description,
        Carbon $created_at,
        string $icon,
        string $color
    ) {
        $this->type = $type;
        $this->description = $description;
        $this->created_at = $created_at;
        $this->icon = $icon;
        $this->color = $color;
    }
}