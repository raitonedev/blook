<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Component - {{ $componentName }}</title>
    </head>

    <style>
        #canva{ position:relative; overflow-y:scroll; transform-origin: top left; }
        #canva-parent, #canva, body{ transition-duration: 280ms; }
    </style>

    <body>
        <div id="canva-parent">
            <div id="canva">

                <!-- Loading target component dynamically with attributes -->
                <x-dynamic-component :component="$componentName" {{ $attributes }}>
                @if($hasSlot)
                    <!-- Injecting slots definitions -->
                    @foreach($slots as $key => $slot)
                        @if($key == "slot")
                            <!-- "Default" slot -->
                            {{ $slot }}
                        @else
                            <!-- Named slot -->
                            <x-slot :name="$key">{{ $slot }}</x-slot>
                        @endif
                    @endforeach
                @endif
                </x-dynamic-component>

            </div>
        </div>

        @forelse(config('blook.assets') as $bundle)
            <!-- Shared assets to load -->
            @include('components.asset', ["bundle" => $bundle])
        @empty
            <!-- No main assets to load. -->
        @endforelse

        @if($hasAssets)
            @forelse($assets as $bundle)
                <!-- Component assets to load -->
                @include('components.asset', ["bundle" => $bundle])
            @empty
                <!-- No main assets to load. -->
            @endforelse
        @endif


        <style>
            .bg-default{ background-color: none; }
            .vewport-default{ width: 100%; height: fit-content; transform: scale(1); }

            @foreach(config('blook.backgrounds') as $background)
                .bg-{{ $background['id'] }} { background-color: {{ $background['color'] }}; }
            @endforeach
            @foreach(config('blook.viewports') as $viewport)
                .viewport-{{ $viewport['id'] }} {
                    width: {{ $viewport['width'] }};
                    height: {{ $viewport['height'] }};
                    border: 2px dashed #ccc;
                    transform:scale({{ $viewport['scale'] }});
                }
            @endforeach
        </style>

    </body>
</html>


