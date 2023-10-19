<?php

namespace Raitone\Blook\Controllers;

use Illuminate\Http\Request;
use Raitone\Blook\Blook;

class BlookController
{

    public function index(Request $request, string $component=null, string $variation=null)
    {
        $blook = new Blook($component, $variation, $request->query(), true);
        return view('blook::index', array_merge([
            "components" => $blook->getComponents(),
            "componentShowRoute" => $blook->getComponentShowRoute()
        ], $blook->getComponentDetails()));
    }


    public function show(Request $request, string $component, string $variation=null)
    {
        $blook = new Blook($component, $variation, $request->query());
        return view('blook::show', $blook->getComponentDetails());
    }
}
