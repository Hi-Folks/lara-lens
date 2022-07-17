<div class="mt-2 mx-1">
    @if (sizeof($rows) > 0)
        <div class="flex space-x-1">
            <span class="text-red">CHECK: issues found</span>
            <span class="flex-1 content-repeat-[─] text-red"></span>
        </div>
    @else
        <div class="flex space-x-1">
            <span class="text-green">CHECK: everything looks good</span>
            <span class="flex-1 content-repeat-[─] text-green"></span>
        </div>
    @endif
    <div>
        @foreach ($rows as $row)
            @php
                $lineType = Arr::get($row, "lineType", HiFolks\LaraLens\ResultLens::LINE_TYPE_DEFAULT);
                $label = Arr::get($row, "label", "");
            @endphp
            @if ($label === "*** HINT")
                <div class="flex space-x-1 mt-1">
                    <span class="px-1 text-gray-100">💡 Hint:</span>
                    <span>
                        <span class="px-0  text-gray-300">
                            {{ Arr::get($row, "value", "") }}
                        </span>
                    </span>
                </div>
            @else
                <div @class([
                        'w-full mx-1 py-1 mt-1 text-center font-bold',
                        'bg-red text-white' => $lineType === HiFolks\LaraLens\ResultLens::LINE_TYPE_ERROR,
                        'bg-yellow text-black' => $lineType === HiFolks\LaraLens\ResultLens::LINE_TYPE_WARNING,
                        'bg-green text-white' => $lineType !== HiFolks\LaraLens\ResultLens::LINE_TYPE_ERROR
                            && $lineType !== HiFolks\LaraLens\ResultLens::LINE_TYPE_WARNING,
                ])>
                    {{ Arr::get($row, "label", "")}}
                </div>
                <div class="mt-1 mx-1 text-gray-300">
                    @if ($lineType === HiFolks\LaraLens\ResultLens::LINE_TYPE_ERROR)
                        {{ Arr::get($row, "value", "")}}
                    @elseif ($lineType === HiFolks\LaraLens\ResultLens::LINE_TYPE_WARNING)
                        {{ Arr::get($row, "value", "")}}
                    @else
                        {{ Str::replace("\\", "/", Arr::get($row, "value", "")) }}
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>
