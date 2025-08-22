<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BaseController extends Controller
{
    protected function renderAdmin(string $component, array $props = [])
    {
        return Inertia::render($component, array_merge([
            'navigation' => config('navigation.admin')
        ], $props));
    }
}
