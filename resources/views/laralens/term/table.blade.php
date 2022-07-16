<div class="mt-1 mx-1">
    <div class="flex space-x-1">
        <span class="flex-1 content-repeat-[─] text-gray">

        </span>

        <span class="text-green">
            {{ $title }}
        </span>
    </div>
    <div>
        <span>
            @foreach ($rows as $row)
                @php
                $lineType = Arr::get($row, "lineType", HiFolks\LaraLens\ResultLens::LINE_TYPE_DEFAULT);
                $label = Arr::get($row, "label", "");
                
                @endphp
                @if ($label === "*** HINT")
                <div class="flex bg-gray-900 space-x-1">
                    <span class=" text-white  font-bold">
                        Hint:
                    </span>
                    <span class="flex-1  content-repeat-[.] text-gray"></span>
                    <span>
                        <span class="px-2  text-gray  font-bold">
                        {{ Arr::get($row, "value", "") }}
                        </span>
                    </span>
                    </div>
                @else
                <div class="flex bg-gray-900 space-x-1">
                    <span class=" text-white  font-bold">
                        {{ Arr::get($row, "label", "")}}
                    </span>
                
                    <span class="flex-1  content-repeat-[.] text-gray"></span>

                    <span>
                        @if ($lineType === HiFolks\LaraLens\ResultLens::LINE_TYPE_ERROR)
                        <span class="text-gray-100 bg-red  font-bold">
                        {{ Arr::get($row, "value", "")}}
                        </span>
                        @elseif ($lineType === HiFolks\LaraLens\ResultLens::LINE_TYPE_WARNING)
                        <span class="text-gray-100 bg-yellow  font-bold">
                        {{ Arr::get($row, "value", "")}}
                        </span>
                        @else
                        
                        <span class="text-gray-100   ">
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