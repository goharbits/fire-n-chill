@if (session('success'))
<div class="alert-container">
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
</div>
@endif

@if($errors->any())
<div class="alert-container">
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif
