<?php


namespace Mock;

use Goodgod\ApiTransform\Resources;
use Illuminate\Support\Collection;

class Model extends Resources
{
    public function relationLoaded(): bool
    {
        return true;
    }
}