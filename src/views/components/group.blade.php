@php $folderMenu = "folderMenu".$loop->iteration @endphp
<div x-data="{ {{ $folderMenu }}: $persist(true) }">

    <div>
        <div class="blook-flex blook-space-between blook-items-center">
            <h4 class="blook-capitalize blook-bold">{{ $group }}</h4>
            <div
                @click="{{ $folderMenu }} = !{{ $folderMenu }}"
                class="blook-muted"
            >
                @include('blook::components.icon', ['icon' => 'plus', 'size' => '16px'])
            </div>
        </div>
    </div>
    

    <div x-show="{{ $folderMenu }}" x-transition>
        @foreach($items as $item => $values)
            @if($values["type"] == "folder")
                <div style="padding-left: calc({{ $loop->iteration }} * 12px);">
                    @include("blook::components.group", ["group" => $item, "items" => $values["children"]])
                </div>
            @else
                <div class="blook-menu-item" style="padding-left: 12px;">

                @if(count($values["variations"]) > 0)

                    @php $variationMenu = "variationsMenu".$loop->parent->iteration.$loop->iteration; @endphp

                    <div x-data="{ {{ $variationMenu }}: $persist(false) }">
                        <div class="blook-flex blook-space-between">
                            <a class="blook-bold" href="{{ route('blook.index', $values['fullname']) }}">{{ $values["name"] }}</a>
                            <div
                                @click="{{ $variationMenu }} = !{{ $variationMenu }}"
                                class="blook-muted"
                            >
                                @include('blook::components.icon', ['icon' => 'plus', 'size' => '16px'])
                            </div>
                        </div>
                            
                            <div
                                x-show="{{ $variationMenu }}"
                                x-transition x-cloak
                                class="blook-variations-bloc">
                                @foreach($values["variations"] as $variation => $props)
                                    <div style="margin-bottom: 4px;">
                                        <a href="{{ route('blook.component.variation', [
                                            'component' => $values['fullname'],
                                            'variation' => $variation
                                        ]) }}">
                                            {{ $props["label"] }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                    </div>

                @else
                    <a class="blook-bold" href="{{ route('blook.index', $values['fullname']) }}">{{ $values["name"] }}</a>
                @endif

        
                </div>
            @endif
        @endforeach
    </div>

</div>
