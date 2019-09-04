@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>

                <div class="card-body">
                    <form action="{{route('profile.changePassword')}}" method="post">
                      @csrf
                      <div class="form-group">
                        <label for="oldPassword">Old Password</label>
                        <input type="password" name="old_password" placeholder="Old Password" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" name="password" placeholder="New Password" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control">
                      </div>
                      <button type="submit" class="btn btn-success btn-block">Edit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
