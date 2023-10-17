<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="manifest" href="/manifest.json" />
        <title>Component Details {{ $componentName }}</title>
    
        <style>
            .rtn-show-section-title{ font-weight: bold; }
            .rtn-muted{ color: #777; }
            .rtn-table td{ padding: 8px; border-bottom: 1px solid #f0f0f0}

            .rtn-show-topbar{
                height: 5%;
                font-family: monospace;
                border-bottom: 2px solid #f0f0f0;
                background-color: #f8f8f8;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0px 20px;
            }

            .rtn-show-body{
                height: 65%;
                overflow-y: scroll;
            }

            .rtn-show-bottombar{
                height: 30%;
                font-family: monospace;
                border-top: 2px solid #f0f0f0;
                background-color: #f8f8f8;
                display: flex;
                justify-content: space-between;
            }

            .rtn-show-bottombar h4{
                position:relative;
                margin-bottom: 8px;
            }

            .rtn-show-bottombar pre{
                height: 100%;
                display:block;
                font-size: 11px;
                overflow-y:scroll;
                border-top: 2px solid #ccc;
                padding: 16px;
                background-color: #111;
                color: #f8f8f8;
                position:relative;
                overflow: scroll;
            }

            .rtn-show-context-bloc{
                width: 40%;
                padding: 16px;
                height: 100%;
                overflow: scroll;
            }

            pre.code {
                white-space: pre-wrap;
            }
            pre.code::before {
                counter-reset: listing;
            }
            pre.code code {
                counter-increment: listing;
            }
            pre.code code::before {
                content: counter(listing) ". ";
                color: #888;
                display: inline-block;
                width: 4em;         /* now works */
                padding-left: auto; /* now works */
                margin-left: auto;  /* now works */
                text-align: left;  /* now works */
            }

        </style>
    </head>
    <body>

    <div style="height: 100svh;">

        <div class="rtn-show-topbar">
            <div>
                {{ $componentName }}
                @if($variation)
                    - [{{ $variation }}]
                @else
                    - Default
                @endif
            </div>
            <div class="rtn-muted">
                Sourcefile {{ $fullComponentPath }}
            </div>
            
        </div>

        <div class="rtn-show-body">
            <!-- Loading target component dynamically -->
            <x-dynamic-component :component="$componentName" {{ $attributes }} />
        </div>

        <div class="rtn-show-bottombar">

            <div style="width: 60%;">
<pre class="code">
@foreach(preg_split("/((\r?\n)|(\r\n?))/", $componentCode) as $line)
<code>{{ $line }}</code>
@endforeach
</pre>
            </div>

            <div class="rtn-show-context-bloc">
                <h4 class="rtn-show-section-title">Current context</h4>
                @if($attributes && count($attributes->getAttributes()) > 0)
                <table class="rtn-table">
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


