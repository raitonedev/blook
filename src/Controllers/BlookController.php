<?php

namespace Raitone\Blook\Controllers;

use Raitone\Blook\Blook;


class BlookController
{

    public function index(string $component=null, string $variation=null)
    {
        $blook = new Blook();
        $componentShowRoute = ""; // Defaults to empty route for component iframe

        // Preparing iframe route to show component default
        if($component){
            $componentShowRoute = route('blook.show', $component);
        }

        // Preparing iframe route to show component with variation
        if($variation){
            $componentShowRoute = route('blook.show.variation', [
                "component" => $component,
                "variation" => $variation
            ]);
        }

        return view('blook::index', [
            "component" => $component,
            "components" => $blook->components,
            "componentShowRoute" => $componentShowRoute
        ]);
    }


    public function show(string $component, string $variation=null)
    {
        $blook = new Blook($component, $variation);
        return view('blook::show', $blook->getComponentDetails());
    }
}
