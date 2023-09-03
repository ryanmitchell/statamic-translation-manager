@extends('statamic::layout')
@section('title', __('Translation Manager') )

@section('content')

    <header class="mb-6">
        <h1>{{ __('Translation Manager') }}</h1>
    </header>

    <h3 class="little-heading pl-0 mb-2">{{ __('Manage Languages') }}</h3>
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

    <h3 class="little-heading pl-0 mb-2">{{ __('Integrity Checks') }}</h3>
    <div class="card p-4 content">
        <div class="flex flex-wrap">
                <a href="{{ cp_route('translation-manager.blueprints') }}" class="w-full lg:w-1/2 p-4 md:flex items-start hover:bg-gray-200 rounded-md group">
                    <div class="h-8 w-8 mr-4 text-gray-800">
                        @cp_svg('icons/light/blueprints')
                    </div>
                    <div class="text-blue flex-1 mb-4 md:mb-0 md:mr-6">
                        <h3>{{ __('Scan blueprints') }}</h3>
                        <p class="text-xs">{{ __('Scan your blueprints for missing strings and add them to your language packs') }}</p>
                    </div>
                </a>
                <a href="{{ cp_route('translation-manager.templates') }}" class="w-full lg:w-1/2 p-4 md:flex items-start hover:bg-gray-200 rounded-md group">
                    <div class="h-8 w-8 mr-4 text-gray-800">
                        @cp_svg('icons/light/code')
                    </div>
                    <div class="text-blue flex-1 mb-4 md:mb-0 md:mr-6">
                        <h3>{{ __('Scan templates') }}</h3>
                        <p class="text-xs">{{ __('Scan your templates for missing strings in { trans } tags and add them to your language packs') }}</p>
                    </div>
                </a>
        </div>
    </div>

@endsection
