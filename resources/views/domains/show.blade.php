@extends('layouts.app')

@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">{{__('layouts.app.site')}}: {{ $domain->name }}</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tr>
                    <td>id</td>
                    <td>{{ $domain->id }}</td>
                </tr>
                <tr>
                    <td>name</td>
                    <td>{{ $domain->name }}</td>
                </tr>
                <tr>
                    <td>created_at</td>
                    <td>{{ $domain->created_at }}</td>
                </tr>
                <tr>
                    <td>updated_at</td>
                    <td>{{ $domain->updated_at }}</td>
                </tr>
            </table>
        </div>
        <h2 class="mt-5 mb-3">Checks</h2>
         {{ Form::open(
           ['action' => ['DomainController@check', $domain->id]]
         ) }}
             @csrf
             {{ Form::button(__('layouts.app.run_check'), [
                 'type'=>'submit',
                 'class' => 'btn btn-primary']
             ) }}
         {{ Form::close() }}
           <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                    <tr>
                        <th>check id</th>
                        <th>status code</th>
                        <th>H1</th>
                        <th>Keywords</th>
                        <th>Description</th>
                        <th>check date</th>
                    </tr>
                @foreach ($domain_checks as $check)
                    <tr>
                        <td>{{ $check->id }}</td>
                        <td>{{ $check->status_code }}</td>
                        <td>{{ Str::of($check->h1)->limit(33) }}</td>
                        <td>{{ Str::of($check->keywords)->limit(33) }}</td>
                        <td>{{ Str::of($check->description)->limit(33) }}</td>
                        <td>{{ $check->created_at }}</td>
                    </tr>
                @endforeach
            </table>
            </div>
@endsection

