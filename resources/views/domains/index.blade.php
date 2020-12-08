@extends('layouts.app')

@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Domains</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Last check</th>
                    <th>Status code</th>
                </tr>

    @foreach ($domains as $domain)
               <tr>
                   <td>{{ $domain->id }}</td>
                   <td><a href="{{ route('domains.show', $domain->id) }}">{{ $domain->name }}</a></td>
                   <td>{{ data_get($lastDomainChecks, $domain->id . '.last_check', null) }}</td>
                   <td>{{ data_get($lastDomainChecks, $domain->id . '.status_code', null) }}</td>
               </tr>
    @endforeach
            </table>
            <div>{{ $domains->links() }}</div>
        </div>
    </div>
@endsection

