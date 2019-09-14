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
                    <form action="{{route('tech.update', ['id'=>$tech->id])}}" method="post" enctype="multipart/form-data">
                      @csrf
                      @method('put')
                      <div class="form-group">
                        <label for="name">Technology Name</label>
                        <input type="text" placeholder="Technology Name" name="name" class="form-control" value="{{$tech->name}}">
                      </div>
                      <div class="form-group">
                        <label for="pic">Technology Picture</label><br>
                        <img src="{{asset('storage/images/tech/'.$tech->pic)}}" class="img-thumbnail" width="20%">
                        <input type="file" placeholder='Technology Picture' name="pic" class="form-control-file">
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
