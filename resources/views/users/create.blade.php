@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-8 offset-2">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Add a Private App</h5> --}}
                        <!-- General Form Elements -->
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <h3 class="pt-4">User Details</h3>
                            <div class="row mb-3 mt-4">
                                <label for="inputText" class="col-sm-4 col-form-label">User Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                        required>
                                    @error('name')
                                        <span class="badge bg-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 mt-4">
                                <label for="inputEmail" class="col-sm-4 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="email" value="{{ old('email') }}"
                                        required>
                                    @error('email')
                                        <span class="badge bg-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 mt-4">
                                <label for="inputPassword" class="col-sm-4 col-form-label">Phone</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                        required>
                                    @error('phone')
                                        <span class="badge bg-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 mt-4">
                                <label for="inputPassword" class="col-sm-4 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="role_id" required>
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $key => $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <span class="badge bg-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 mt-4">
                                <label for="inputPassword" class="col-sm-4 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="password"
                                        value="{{ old('password') }}" min="6" required>
                                    @error('password')
                                        <span class="badge bg-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form><!-- End General Form Elements -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script type="text/javascript">
        $("ul#users").siblings('a').attr('aria-expanded', 'true');
        $("ul#users").addClass("show");
        $("#create").addClass("active");
    </script>
@endsection
