@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$title}}</div>

                <div class="card-body">
                    <form action="{{route('profile.update')}}" method="post" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group">
                        <label for="avatar">Avatar</label><br>
                        <img class="img-thumbnail" src="{{asset('storage/images/avatar/'.$user->profile->avatar)}}" alt="">
                        <input type="file" name="avatar" class="form-control-file">
                      </div>
                      <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Username" value="{{$user->username}}">
                      </div>
                      <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{$user->email}}">
                      </div>
                      <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Name" value="{{$user->name}}">
                      </div>
                      <div class="form-group">
                        <label for="about">About</label>
                        <textarea name="about" class="form-control" cols="30" rows="5" placeholder="About">{{$user->profile->about}}</textarea>
                      </div>
                      <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="number" name="phone" class="form-control" placeholder="Phone Number" value="{{$user->profile->phone}}">
                      </div>
                      <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" cols="30" rows="5" class="form-control" placeholder="Address">{{$user->profile->address}}</textarea>
                      </div>
                      <div class="form-group">
                        <label for="instagram">Instagram</label>
                        <input type="text" name="instagram" class="form-control" placeholder="Instagram" value="{{$user->profile->instagram}}">
                      </div>
                      <div class="form-group">
                        <label for="facebook">Facebook</label>
                        <input type="text" name="facebook" class="form-control" placeholder="Facebook" value="{{$user->profile->facebook}}">
                      </div>
                      <div class="form-group">
                        <label for="twitter">Twitter</label>
                        <input type="text" name="twitter" class="form-control" placeholder="Twitter" value="{{$user->profile->twitter}}">
                      </div>
                      <div class="form-group">
                        <label for="linkedin">LinkedIn</label>
                        <input type="text" name="linkedin" class="form-control" placeholder="LinkedIn" value="{{$user->profile->linkedin}}">
                      </div>
                      <div class="form-group">
                        <label for="github">Github</label>
                        <input type="text" name="github" class="form-control" placeholder="Github" value="{{$user->profile->github}}">
                      </div>
                      <div class="form-group">
                        <label for="youtube">YouTube</label>
                        <input type="text" name="youtube" class="form-control" placeholder="YouTube" value="{{$user->profile->youtube}}">
                      </div>
                      <button type="submit" class="btn btn-success btn-block">Edit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
