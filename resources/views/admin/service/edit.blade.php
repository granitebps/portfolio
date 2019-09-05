@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">{{$title}}</h3>
                </div>

                <div class="card-body">
                    <form action="{{route('service.update', ['id'=>$service->id])}}" method="post">
                      @csrf
                      @method('put')
                      <div class="form-group">
                        <label for="name">Service Name</label>
                        <input type="text" placeholder="Service Name" name="name" class="form-control" value="{{$service->name}}">
                      </div>
                      <div class="form-group">
                        <label for="icon">Service Icon | Insert Font Awesome HTML Tag</label>
                        <input type="text" placeholder='Example : <i class="fab fa-laravel"></i>' name="icon" class="form-control" value="{{$service->icon}}">
                      </div>
                      <div class="form-group">
                        <label for="desc">Service Description</label>
                        <textarea name="desc" class="form-control" cols="30" rows="5" placeholder="Service Description">{{$service->desc}}</textarea>
                      </div>
                      <button type="submit" class="btn btn-success btn-block">Edit</button>
                    </form>
                    <hr>
                    <a href="{{url()->previous()}}" class="btn btn-warning btn-block">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
