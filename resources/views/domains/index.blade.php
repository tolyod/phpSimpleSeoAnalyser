@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Domains</h1>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Status Code</th>
        </tr>

    @foreach ($domains as $domain)
       <tr>
           <td>{{ $domain->id }}</td>
           <td><a href="{{ route('domains.show', $domain->id) }}">{{ $domain->name }}</a></td>
           <td>200</td>
       </tr>
    @endforeach
@endsection

