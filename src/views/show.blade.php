<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Component Details {{ $componentName }}</title>
        <style>
            /* Utilities */
            .blook-show-section-title{ font-weight: bold; }
            .blook-muted{ color: #777; }
            .blook-table td{ padding: 8px; border-bottom: 1px solid #f0f0f0}

            /* Code block with numbered lines */
            pre.code { white-space: pre-wrap; }
            pre.code::before { counter-reset: listing;}
            pre.code code { counter-increment: listing; }
            pre.code code::before { content: counter(listing) ". "; color: #888; display: inline-block; width: 3em; }
            .blook-show-bottombar pre{
                height: 100%;
                display:block;
                font-size: 11px;
                border-top: 2px solid #ccc;
                padding: 16px;
                background-color: #111;
                color: #f8f8f8;
                position:relative;
                overflow: scroll;
            }

            /* Structure */
            .blook-show-topbar{ height: 5%;
                font-family: monospace;
                border-bottom: 2px solid #f0f0f0;
                background-color: #f8f8f8;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0px 20px;
            }

            .blook-show-body{ 
                height: 65%; overflow-y: scroll;
            }

            .blook-show-bottombar{
                height: 30%;
                font-family: monospace;
                border-top: 2px solid #f0f0f0;
                background-color: #f8f8f8;
                display: flex;
                justify-content: space-between;
            }

            .blook-show-bottombar h4{
                position:relative;
                margin-bottom: 8px;
            }

            .blook-show-context-bloc{
                width: 40%;
                padding: 16px;
                height: 100%;
                overflow: scroll;
            }

        </style>
    </head>
    <body>

    <div style="height: 100svh;">

        <div class="blook-show-topbar">
            <div>
                {{ $componentName }}
                @if($variation)
                    - <span class="blook-muted">[{{ $variation }}]</span>
                @else
                    - Default
                @endif
            </div>
            <div class="blook-muted">
                Sourcefile {{ $fullComponentPath }}
            </div>
            
        </div>

        <div class="blook-show-body">
            <!-- Loading target component dynamically with attributes -->
            <x-dynamic-component :component="$componentName" {{ $attributes }} />
        </div>

        <div class="blook-show-bottombar">

            <div style="width: 60%;">
<pre class="code">
@foreach(preg_split("/((\r?\n)|(\r\n?))/", $componentCode) as $line)
<code>{{ $line }}</code>
@endforeach
</pre>
            </div>

            <div class="blook-show-context-bloc">
                <h4 class="blook-show-section-title">Current context</h4>
                @if($attributes && count($attributes->getAttributes()) > 0)
                <table class="blook-table">
                    @foreach($attributes->getAttributes() as $attr => $value)
                        <tr><td>{{ $attr }}</td><td>{{ $value }}</td>
                    @endforeach
                </table>
                @else
                    No custom attributes provided.
                @endif
            </div>

        </div>
    </div>


        @forelse(config('blook.assets') as $bundle)
            <script type="module" src="http://localhost:3002/resources/js/bundles/{{ $bundle }}.js"></script>
        @empty
        @endforelse
    </body>
</html>


