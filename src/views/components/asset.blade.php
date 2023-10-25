
@if($blook->fileContext->hasDetectedConfig)

    @php
        $port = ":3000";
        if($blook->fileContext->hasDetectedPort){
            $port = ":".$blook->fileContext->bundlerCustomPort;
        }
    @endphp

    @if($blook->fileContext->bundlerConfigFile == \Raitone\Blook\Services\FileContextService::VITE_CONFIG_FILE)

        @env(['production', 'test', 'staging'])
            @php
                $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
                $path = 'resources/js/bundles/' . $bundle . '.js';
            @endphp

            <script type="module" src="/build/{{$manifest[$path]['file']}}"></script>

            @if(isset($manifest[$path]['css']))
                <link rel="stylesheet" href="/build/{{$manifest[$path]['css'][0]}}">
            @endif
        @else
            <script type="module" src="http://localhost{{ $port }}/resources/js/bundles/{{ $bundle }}.js"></script>
        @endenv

    @endif

@endif


