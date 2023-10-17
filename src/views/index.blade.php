<!DOCTYPE html>
<html class="blook-null" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Components - {{ config('app.name') }}</title>

        <style>
            .blook-title{ font-weight: bold; font-size: 1.2em;}
            .blook-container{ display:flex; height: 100svh; overflow:hidden; }
            .blook-null{ margin: 0 !important; padding: 0 !important; }
            .blook-body{ height: 100vh; }
            .blook-sidebar{
                font-family: Arial, Helvetica, sans-serif;
                width: 15vw;
                background: #f8f8f8;
                border-right: 2px solid #f0f0f0;
                font-size: .8em;
                padding: 24px;
                min-height: 100svh;
                overflow-y: scroll;
            }
            .blook-variations-bloc{
                margin-top: 6px;
                margin-left: 16px;
            }
            .blook-group-name{ text-transform: capitalize;}
            .blook-menu-item{margin-top: 8px;}
            .blook-menu-item a{ text-transform: capitalize; text-decoration: none;}
            .blook-iframe{ border: none; width: 85vw; height: 100svh; overflow:hidden;}
        </style>
    </head>

    <body class="blook-null blook-body">
        <div class="blook-container">
            <div class="blook-sidebar">

                <div>
                    <span class="blook-title">{{ config("blook.title") }}</span>
                </div>

                @foreach($components as $group => $item)
                    @include('blook::components.group', ['group' => $group, 'items' => $item["children"]])
                @endforeach

            </div>
            <div class="blook-workspace">
                <iframe class="blook-iframe" src="{{ $componentShowRoute }}" title="Component Detail"></iframe>
            </div>
        </div>
    </body>
</html>
