@extends('layouts.app')

@section('content')
    <div class="jumbotron">
        <h1 class="display-3">Page Analyzer</h1>
        <p class="lead">Check web pages for free</p>
        {{ App::environment() }}
        {{ dump(json_encode($_ENV)) }}
        <hr class="my-4">
        {{ Form::open(
                ['action' => 'DomainController@store',
                'class' => 'd-flex justify-content-center form-inline']
            ) }}
            @csrf
            @include('domains.form')
            {{ Form::submit(__('layouts.app.add'), ['class' => 'btn btn-lg btn-primary ml-3']) }}
        {{ Form::close() }}
    </div>
@endsection

