<div>
    <h4 class="rtn-group-name">{{ $group }}</h4>

    @foreach($items as $item => $values)
        @if($values["type"] == "folder")
            <div>
                @include("blook::components.group", ["group" => $item, "items" => $values["children"]])
            </div>
        @else
            <div class="rtn-menu-item">
                <a href="{{ route('blook.index', $values['fullname']) }}">{{ $values["name"] }}</a>

                @if(count($values["variations"]) > 0)
                    <div class="rtn-variations-bloc">
                        @foreach($values["variations"] as $variation => $props)
                            <div>
                                <a href="{{ route('blook.component.variation', [
                                    'component' => $values['fullname'],
                                    'variation' => $variation
                                ]) }}">{{ $props["label"] }}</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    @endforeach
</div>
