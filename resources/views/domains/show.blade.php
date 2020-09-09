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
                        <td>200</td>
                        <td>2020-03-30 19:53:43</td>
                    </tr>
                                    <tr>
                        <td>200</td>
                        <td>2020-04-05 21:40:43</td>
                    </tr>
                                    <tr>
                        <td>200</td>
                        <td>2020-04-21 00:49:17</td>
                    </tr>
                                    <tr>
                        <td>200</td>
                        <td>2020-05-03 02:40:45</td>
                    </tr>
                                    <tr>
                        <td>200</td>
                        <td>2020-05-03 02:40:48</td>
                    </tr>
                            </table>
            </div>
@endsection

