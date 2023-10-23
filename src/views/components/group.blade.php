@php
    $folderMenu = "folderMenu".$id;
@endphp


<li x-data="{ {{ $folderMenu }}: $persist(true) }">

    <!-- FOLDER -->
    <span
        @click="{{ $folderMenu }} = !{{ $folderMenu }}"
        class="bg-gray-50 cursor-pointer text-indigo-600 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold"
    >
        <span class="text-gray-400">@include('blook::components.icon', ['icon' => 'folder'])</span>
        <h4 class="font-semibold capitalize">{{ $group }}</h4>
    </span>

    <!-- FOLDER SUB-BLOC -->
    <span
        x-show="{{ $folderMenu }}"
        x-transition
        class="block pl-4 pt-2 pb-4"
    >
        @foreach($items as $item => $values)
            @if($values["type"] == "folder")
            <ul>
                @include("blook::components.group", ["group" => $item, "items" => $values["children"], "id" => $id."sub".$loop->iteration])
            </ul>
            @else

                @if(count($values["variations"]) > 0)

                    @php $variationMenu = "variationsMenu".$id.$loop->iteration; @endphp

                    <div x-data="{ {{ $variationMenu }}: $persist(false) }">
                        <div class="flex justify-between">

                            <div class="flex gap-2 items-center">
                                <span class="text-gray-400">
                                    @include('blook::components.icon', ['icon' => 'component'])
                                </span>
                                <a class="font-semibold text-sm capitalize" href="{{ route('blook.index', $values['fullname']) }}">{{ $values["name"] }}</a>
                            </div>

                            <div
                                @click="{{ $variationMenu }} = !{{ $variationMenu }}"
                                class="text-indigo-300"
                            >
                                @include('blook::components.icon', ['icon' => 'plus'])
                            </div>
                        </div>
                            
                            <!-- VARIATIONS ITEMS -->
                            <div
                                x-show="{{ $variationMenu }}"
                                x-transition x-cloak
                                class="pl-2 mt-4 mb-8"
                            >
                                @foreach($values["variations"] as $variation => $props)
                                    <div class="mb-2 text-sm">
                                        <a href="{{ route('blook.component.variation', [
                                            'component' => $values['fullname'],
                                            'variation' => $variation
                                        ]) }}">
                                            <span class="text-gray-400 mr-2">–</span> {{ $props["label"] }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                    </span>

                @else
                    <!-- STANDALONE COMPONENT -->
                    <span class="flex gap-2 items-center mt-2">
                        <span class="text-gray-400">
                            @include('blook::components.icon', ['icon' => 'component'])
                        </span>
                        <a
                            class="font-semibold capitalize text-sm"
                            href="{{ route('blook.index', $values['fullname']) }}"
                        >{{ $values["name"] }}</a>
                    </span>
                @endif


            @endif
        @endforeach
    </span>
</li>
