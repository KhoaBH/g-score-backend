@extends('layouts.app')

@section('content')

    <div x-show="currentTab === 'search'" x-transition>
        @include('partials.search')
    </div>

    <div x-show="currentTab === 'chart'" x-transition>
        @include('partials.chart')
    </div>

    <div x-show="currentTab === 'ranking'" x-transition>
        @include('partials.ranking')
    </div>

@endsection