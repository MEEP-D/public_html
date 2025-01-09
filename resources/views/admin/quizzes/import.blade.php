@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('data'))
    <pre>{{ print_r(session('data'), true) }}</pre>
@endif
