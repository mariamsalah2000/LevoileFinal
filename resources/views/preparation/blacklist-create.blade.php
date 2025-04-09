@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <div class="row">
        <div class="col-8">
            <h1>BlackList</h1>
            <nav>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item">Create BlackList</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create BlackList</h5>
                    <!-- Floating Labels Form -->
                    <form class="row g-3" method="POST" action="{{route('blacklist.store')}}">
                        @csrf
                        <div class="col-md-12">
                            <div class="form-floating">
                            <input type="text" class="form-control" id="floatingName" name="name" placeholder="Add Name" value="{{ old('name') }}" required>
                            <label for="floatingName">Name</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="tel" class="form-control" name="phone" id="floatingProductType" placeholder="Add Phone (+2)" value="{{ old('phone') }}" required>
                                    <label for="floatingProductType">Phone</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                            <textarea class="form-control" placeholder="Add Note" id="floatingTextarea" style="height: 100px;" name="note">{{ old('note') }}</textarea>
                            <label for="floatingTextarea">Note</label>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="width:40%">Create</button>
                        </div>
                    </form><!-- End floating Labels Form -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

