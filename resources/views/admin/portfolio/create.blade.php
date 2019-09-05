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
                    <form action="{{route('portfolio.store')}}" method="post" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group">
                        <label for="name">Portfolio Name</label>
                        <input type="text" placeholder="Portfolio Name" name="name" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="desc">Portfolio Description</label>
                        <textarea name="desc" class="form-control" cols="30" rows="5"></textarea>
                      </div>
                      <div class="form-group">
                        <label for="thumbnail">Portfolio Thumbnail</label>
                        <input type="file" placeholder='Portfolio Thumbnail' name="thumbnail" class="form-control-file">
                      </div>
                      <div class="form-group">
                        <label for="pic">Portfolio Picture</label>
                        <input type="file" placeholder='Portfolio Picture' name="pic[]" class="form-control-file" multiple>
                      </div>
                      <div class="form-group">
                        <label for="url">Portfolio URL</label>
                        <input type="text" placeholder="Portfolio URL" name="url" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="type">Portfolio Type</label>
                        <select name="type" class="form-control">
                          <option value="" disabled selected>-- Select Portfolio Type --</option>
                          <option value="1">Personal Project</option>
                          <option value="2">Client Project</option>
                        </select>
                      </div>
                      <button type="submit" class="btn btn-success btn-block">Save</button>
                    </form>
                    <hr>
                    <a href="{{url()->previous()}}" class="btn btn-warning btn-block">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
