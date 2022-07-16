<div class="mt-1 mx-1">
    @if (sizeof($rows) > 0)
    <div class="flex space-x-1">
        <span class="flex-1 content-repeat-[─] text-red">
        </span>
        <span class="text-red">
        CHECK: issues found
        </span>
    </div>
    @else
    <div class="flex space-x-1">
        <span class="flex-1 content-repeat-[─] text-green">
        </span>
        <span class="text-green">
        CHECK: everything looks good
        </span>
    </div>
    @endif
    <div>
        <span>
            @foreach ($rows as $row)
                @php
                $lineType = Arr::get($row, "lineType", HiFolks\LaraLens\ResultLens::LINE_TYPE_DEFAULT);
                $label = Arr::get($row, "label", "");
                @endphp 
                
                @if ($label === "*** HINT")
                <div class="flex bg-gray-900 space-x-1">
                    <span class="px-1   text-gray-100">
                    💡 Hint:
                    </span>
                    
                    <span>
                        <span class="px-0  text-gray-300">
                        {{ Arr::get($row, "value", "") }}
                        </span>
                    </span>
                    </div>
                @else
                <div class="">
                </div>
                <div class="flex bg-gray-900 space-x-0  ">
                    <span class=" text-gray-800 px-1 bg-gray-200 font-bold">
                    @if ($lineType === HiFolks\LaraLens\ResultLens::LINE_TYPE_ERROR)
                    ❌
                    @elseif ($lineType === HiFolks\LaraLens\ResultLens::LINE_TYPE_WARNING)
                    ⚠️
                    @else
                    ✔️
                    @endif
                    &nbsp;{{ Arr::get($row, "label", "")}}
                    </span>
                
                    <span class="flex-1  content-repeat-[.] text-gray"></span>

                    <span>
                        @if ($lineType === HiFolks\LaraLens\ResultLens::LINE_TYPE_ERROR)
                        <span class="px-0 bg-red text-white  font-bold">
                        {{ Arr::get($row, "value", "")}}
                        </span>
                        @elseif ($lineType === HiFolks\LaraLens\ResultLens::LINE_TYPE_WARNING)
                        <span class="px-0 bg-yellow text-gray  font-bold">
                        {{ Arr::get($row, "value", "")}}
                        </span>
                        @else
                        
                        <span class="px-0 bg-green text-gray  font-bold">
                        {{ Str::replace("\\", "/", Arr::get($row, "value", "")) }}
                        </span>
                        @endif

                    </span>
                </div>
                @endif
            @endforeach
        </span>
    </div>
</div>