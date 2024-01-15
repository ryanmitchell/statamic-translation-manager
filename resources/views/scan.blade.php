@extends('statamic::layout')
@section('title', $title )

@section('content')

    <header class="mb-6">
        <h1>{{ $title }}</h1>
    </header>

    @if (count($missing) < 1)
    <div class="flex justify-center text-center mt-16">
        <div class="bg-white rounded-full px-6 py-2 shadow-sm text-sm text-gray-700">{{ __('statamic-translation-manager::default.no_missing_translations') }}</div>
    </div>
    @endif

    @foreach ($missing as $locale => $strings)
    <div class="card mb-10">
        <div class="flex justify-between mb-6">
            <div>
                <h1>{{ $locale }}</h1>
                <h5 class="date">{{ trans_choice('statamic-translation-manager::default.missing_translations', count($strings), ['count' => count($strings)]) }}</h5>
            </div>

            <div>
                <a class="btn" href="{{ cp_route($edit_route, ['locale' => $locale]) }}">{{ __('statamic-translation-manager::default.add_to_pack') }}</a>
            </div>
        </div>

        <div class="card-body">
            <div>
                <ul>
                @foreach ($strings as $string)
                    <li>{{ $string }}</li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endforeach

@endsection
