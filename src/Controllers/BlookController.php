<?php

namespace Raitone\Blook\Controllers;

use Illuminate\Http\Request;
use Raitone\Blook\Blook;

class BlookController
{

    public function index(Request $request, string $component=null, string $variation=null)
    {
        $blook = new Blook();

        // Preparing iframe route to show component
        if($component){
            $routeName = 'blook.show';
            $context = [$component];
        }

        // Preparing iframe route to show component with variation
        if($variation){
            $routeName = 'blook.show.variation';
            $context = [$component, $variation];
        }

        // Repopulating context with query args if there are some
        foreach($request->query() as $arg => $value){
            $context[$arg] = $value;
        }

        $componentShowRoute = isset($routeName) ? route($routeName, $context) : "";

        return view('blook::index', [
            "component" => $component,
            "components" => $blook->components,
            "componentShowRoute" => $componentShowRoute
        ]);
    }


    public function show(Request $request, string $component, string $variation=null)
    {
        $blook = new Blook($component, $variation, $request->query());
        return view('blook::show', $blook->getComponentDetails());
    }
}
