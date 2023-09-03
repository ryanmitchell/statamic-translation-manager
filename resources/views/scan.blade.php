@extends('statamic::layout')
@section('title', $title )

@section('content')

    <header class="mb-6">
        <h1>{{ $title }}</h1>
    </header>

    @if (count($missing) < 1)
    <div class="flex justify-center text-center mt-16">
        <div class="bg-white rounded-full px-6 py-2 shadow-sm text-sm text-gray-700">{{ __('No missing translations! You\'re doing great!') }}</div>
    </div>
    @endif

    @foreach ($missing as $locale => $strings)
    <div class="card mb-10">
        <div class="flex justify-between mb-6">
            <div>
                <h1>{{ $locale }}</h1>
                <h5 class="date">{{ count($strings) }} missing translations</h5>
            </div>

            <div>
                <a class="btn" href="{{ cp_route($edit_route, ['locale' => $locale]) }}">Add To Language Pack</a>
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
