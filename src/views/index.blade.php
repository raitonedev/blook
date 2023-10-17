<!DOCTYPE html>
<html class="rtn-null" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Components - {{ config('app.name') }}</title>

        <style>
            .rtn-container{ display:flex; height: 100svh; overflow:hidden; }
            .rtn-null{ margin: 0 !important; padding: 0 !important; }
            .rtn-body{ height: 100vh; }
            .rtn-sidebar{
                font-family: Arial, Helvetica, sans-serif;
                width: 15vw;
                background: #f8f8f8;
                border-right: 2px solid #f0f0f0;
                font-size: .8em;
                padding: 24px;
                min-height: 100svh;
                overflow-y: scroll;
            }
            .rtn-variations-bloc{
                margin-top: 6px;
                margin-left: 16px;
            }
            .rtn-group-name{ text-transform: capitalize;}
            .rtn-menu-item{margin-top: 8px;}
            .rtn-menu-item a{ text-transform: capitalize; text-decoration: none;}
            .rtn-iframe{ border: none; width: 85vw; height: 100svh; overflow:hidden;}
        </style>

    </head>

    <body class="rtn-null rtn-body">
        <div class="rtn-container">
            <div class="rtn-sidebar">

                <div>
                    {{ config("blook.title") }}
                </div>

                @foreach($components as $group => $item)
                    @include('blook::components.group', ['group' => $group, 'items' => $item["children"]])
                @endforeach

            </div>
            <div class="rtn-workspace">
                <iframe class="rtn-iframe" src="{{ $componentShowRoute }}" title="Component Detail"></iframe>
            </div>
        </div>
    </body>
</html>
