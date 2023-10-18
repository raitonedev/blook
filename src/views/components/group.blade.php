@php $folderMenu = "folderMenu".$loop->iteration @endphp
<div style="margin-top: 8px; margin-bottom: 8px; border-bottom: 1px solid #eee; padding: 8px;" x-data="{ {{ $folderMenu }}: $persist(true) }">

    <div @click="{{ $folderMenu }} = !{{ $folderMenu }}" style="display:flex; gap: 4px;">
        <span class="blook-muted">
            @include('blook::components.icon', ['icon' => 'folder', 'size' => '15px'])
        </span>
        <h4 class="blook-capitalize blook-bold">{{ $group }}</h4>
    </div>

    <div style="margin-top: 16px;" x-show="{{ $folderMenu }}" x-transition>
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

                            <div class="blook-flex blook-items-center" style="gap:4px;">
                                <span class="blook-muted" style="margin-top: 2px;">
                                    @include('blook::components.icon', ['icon' => 'component', 'size' => '18px'])
                                </span>
                                <a class="blook-bold" href="{{ route('blook.index', $values['fullname']) }}">{{ $values["name"] }}</a>
                            </div>

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
                                class="blook-variations-bloc"
                                style="margin-bottom: 8px;"
                            >
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
                    <div class="blook-flex blook-items-center" style="gap:4px;">
                        <span class="blook-muted" style="margin-top: 2px;">
                            @include('blook::components.icon', ['icon' => 'component', 'size' => '18px'])
                        </span>
                        <a class="blook-bold" href="{{ route('blook.index', $values['fullname']) }}">{{ $values["name"] }}</a>
                    </div>
                @endif

        
                </div>
            @endif
        @endforeach
    </div>

</div>
