@if (session()->has('success'))
    <div class="row">
        <div class="alert alert-primary text-center" style="color:rgb(0, 0, 0)">
            <h5>{{ session()->get('success') }}</h5>
        </div>
    </div>
@endif

@if (session()->has('errors'))
    <div class="row">
        <div class="alert alert-danger text-center" style="color:rgb(0, 0, 0)">
            <h5>{{ session()->get('errors') }}</h5>
        </div>
    </div>
@endif
