<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Http\Controllers\Controller;

class VideoController extends BasicCrudController
{

    private $rules = [];

    protected function model()
    {
        return Video::class;
    }

    protected function rulesStore()
    {
        // return $this->rules;
    }

    protected function rulesUpdate()
    {
        // return $this->rules;
    }
}
