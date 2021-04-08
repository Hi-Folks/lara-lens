<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <title>LaraLens</title>
</head>
<body>


<nav class="relative flex flex-wrap items-center justify-between px-2 py-3 navbar-expand-lg bg-yellow-500 mb-3">
    <div class="container px-4 mx-auto flex flex-wrap items-center justify-between">
        <div class="w-full relative flex justify-between lg:w-auto  px-4 lg:static lg:block lg:justify-start  font-bold leading-relaxed inline-block mr-4 py-2 whitespace-no-wrap text-white">

            LaraLens

        </div>
        <div class="lg:flex flex-grow items-center" id="example-navbar-warning">
            <ul class="flex flex-col lg:flex-row list-none ml-auto">
                <li class="nav-item">
                    <a class="px-3 py-2 flex items-center text-xs uppercase font-bold leading-snug text-white hover:opacity-75" href="https://medium.com/@robertodev/laralens-a-laravel-command-for-inspecting-configuration-2bbb4e714cf7">
                        Tutorial
                    </a>
                </li>
                <li class="nav-item">
                    <a class="px-3 py-2 flex items-center text-xs uppercase font-bold leading-snug text-white hover:opacity-75" href="https://packagist.org/packages/hi-folks/lara-lens">
                        Packagist
                    </a>
                </li>
                <li class="nav-item">
                    <a class="px-3 py-2 flex items-center text-xs uppercase font-bold leading-snug text-white hover:opacity-75" href="https://github.com/Hi-Folks/lara-lens">
                        GitHub
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@foreach( $data as $item)
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{$item['title']}}
        </h3>
        <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500">
            {{$item['description']}}
        </p>
    </div>
    <div>
        <dl class="divide-y divide-gray-400">
            @foreach( $item['data'] as $line)
            <div class="{{ $loop->even ? "bg-gray-200" : "bg-white" }} px-4 py-1 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm leading-5 font-medium text-gray-700">
                    {{ $line['label'] }}
                </dt>
                <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $line['value'] }}

                </dd>
            </div>
            @endforeach
        </dl>
    </div>
</div>
@endforeach

@foreach( $checks as $item)
    @if(\Illuminate\Support\Arr::get($item, "lineType", \HiFolks\LaraLens\ResultLens::LINE_TYPE_DEFAULT) === \HiFolks\LaraLens\ResultLens::LINE_TYPE_HINT)
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
            <p class="font-bold">{{ $item['label'] }}</p>
            <p>{{ $item['value']  }}</p>
        </div>

    @elseif(\Illuminate\Support\Arr::get($item, "lineType", \HiFolks\LaraLens\ResultLens::LINE_TYPE_DEFAULT) === \HiFolks\LaraLens\ResultLens::LINE_TYPE_ERROR)
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p class="font-bold">{{ $item['label'] }}</p>
            <p>{{ $item['value']  }}</p>
        </div>
    @elseif(\Illuminate\Support\Arr::get($item, "lineType", \HiFolks\LaraLens\ResultLens::LINE_TYPE_DEFAULT) === \HiFolks\LaraLens\ResultLens::LINE_TYPE_WARNING)
        <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
            <p class="font-bold">{{ $item['label'] }}</p>
            <p>{{ $item['value']  }}</p>
        </div>
    @else
        <div class="bg-grey-100 border-l-4 border-grey-500 text-grey-700 p-4" role="alert">
            <p class="font-bold">{{ $item['label'] }}</p>
            <p>{{ $item['value']  }}</p>
        </div>

    @endif

@endforeach

</body>
</html>
