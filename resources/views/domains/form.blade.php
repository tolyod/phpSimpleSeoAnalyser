@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{ Form::text('name', '', [
    'class' => 'form-control form-control-lg',
    'placeholder' => 'https://www.example.com
']) }}<br>
