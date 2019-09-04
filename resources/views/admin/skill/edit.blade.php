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
                    <form action="{{route('skill.update', ['id'=>$skill->id])}}" method="post">
                      @csrf
                      @method('put')
                      <div class="form-group">
                        <label for="name">Skill Name</label>
                        <input type="text" placeholder="Skill Name" name="name" class="form-control" value="{{$skill->name}}">
                      </div>
                      <div class="form-group">
                        <label for="percentage">Skill Percentage</label>
                        <input type="number" placeholder="Skill Percentage" name="percentage" class="form-control" value="{{$skill->percentage}}">
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
