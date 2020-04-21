@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ $domain->name }}</h1>
    <table class="table">
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
@endsection

