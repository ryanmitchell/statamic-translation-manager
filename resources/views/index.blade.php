@extends('statamic::layout')
@section('title', __('Translation Manager') )

@section('content')

    <header class="mb-6">
        <h1>{{ __('Translation Manager') }}</h1>
    </header>

    <h3 class="little-heading pl-0 mb-2">{{ __('Languages') }}</h3>
    <div class="card p-0 mb-4">
        <table class="data-table">
            @foreach ($locales as $locale)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="w-4 h-4 mr-4">@cp_svg('icons/light/content-writing')</div>
                                <a href="{{ cp_route('translation-manager.edit', $locale['name']) }}">{{ $locale['name'] }}</a>
                            </div>
                        </td>
                    </tr>
            @endforeach
        </table>
    </div>
    
@endsection