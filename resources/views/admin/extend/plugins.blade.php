@extends('layouts.admin')

@section('title-after')
    <a href="{{ route('find_plugins') }}" class="btn btn-primary"><i class="la la-plug"></i> Find New Plugins</a>
@endsection

@section('content')

<div class="container mt-5">
    @php
    $allCount = count($plugins);
    $active_plugins = (array) json_decode(get_option('active_plugins'), true);
    $activeCount = count($active_plugins);
    $inActiveCount = 3 - $activeCount;
    @endphp

    @if($allCount)

    <div class="container p-3">

    <div class="plugins-list-stats mb-4">
        <p class="text-muted">
            All ({{ count($plugins) }}) | {{ __('Active') }} ({{ $activeCount }}) | {{ __('Inactive') }} ({{ $inActiveCount }})
        </p>
    </div>

    <div class="row mb-4">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Plugin') }}</th>
                            <th>{{ __('Description') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plugins as $plugin)
                        <tr class="plugin-row-{{ $plugin->activated ? 'activated' : 'deactivated' }}">
                            <td>
                                <h5 class="plugin-name mb-0">{{ $plugin->name }}</h5>
                                <p class="mb-0">
                                    @if($plugin->activated)
                                    <a class="activate-link" href="{{ route('plugin_action', ['action' => 'deactivate', 'plugin' => $plugin->basename]) }}">{{ __('Deactivate') }}</a>
                                    @else
                                    <a class="activate-link" href="{{ route('plugin_action', ['action' => 'activate', 'plugin' => $plugin->basename]) }}">{{ __('Activate') }}</a>
                                    @endif
                                </p>
                            </td>
                            <td>
                                <p>{{ $plugin->description }}</p>
                                <p class="text-muted mb-0">{{ __('Version') }}: {{ $plugin->version }} | {{ __('By') }}
                                    @if($plugin->author_url)
                                    <a href="{{ $plugin->author_url }}" target="_blank">{{ $plugin->author }}</a>
                                    @else
                                    {{ $plugin->author }}
                                    @endif
                                    @if($plugin->url)
                                    | <a href="{{ $plugin->url }}" target="_blank">{{ __('View Details') }}</a>
                                    @endif
                                </p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @else

    <div class="alert alert-info mt-5" role="alert">
        <h4 class="alert-heading">{{ __('No Available Plugins') }}</h4>
        <p>{{ __('Plugins extend and expand the functionality of Teachify LMS. Check out below plugins and consider using these plugins to take your application to the next level.') }}</p>
    </div>

    @if(is_array($extended_plugins) && count($extended_plugins))
    <div class="row mt-5">
        @foreach($extended_plugins as $extended_plugin)
        @php
        $name = array_get($extended_plugin, 'name');
        $desc = array_get($extended_plugin, 'desc');
        $url = array_get($extended_plugin, 'url');
        $thumbnail_url = array_get($extended_plugin, 'thumbnail_url');
        @endphp
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                @if($thumbnail_url)
                <a href="{{ $url }}" target="_blank">
                    <img class="card-img-top" src="{{ $thumbnail_url }}" alt="{{ $name }}">
                </a>
                @endif

                <div class="card-body">
                    <a href="{{ $url }}" target="_blank" class="plugin-name"><h5 class="card-title">{{ $name }}</h5></a>
                    <p class="card-text">{{ $desc }}</p>
                    <a href="{{ $url }}" target="_blank" class="btn btn-dark">{{ __('View Details') }}</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @endif

</div>

@endsection
