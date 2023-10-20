<?php

namespace Raitone\Blook\Controllers;

use Illuminate\Http\Request;
use Raitone\Blook\Blook;

class BlookController
{

    public function index(Request $request, string $component=null, string $variation=null)
    {
        $blook = new Blook($component, $variation, $request->query(), true);

        $baseContext = [
            "components" => $blook->getComponents(),
            "componentShowRoute" => $blook->getComponentShowRoute()
        ];

        return view('blook::index', array_merge($baseContext, $blook->getComponentDetails()));
    }


    public function show(Request $request, string $component, string $variation=null)
    {
        $blook = new Blook($component, $variation, $request->query());

        $baseContext = [
            "hasSlot" => $blook->componentHasProperty(Blook::SLOTS),
            "hasAssets" => $blook->componentHasProperty(Blook::ASSETS),
        ];

        return view('blook::show', array_merge($baseContext, $blook->getComponentDetails()));
    }
}
