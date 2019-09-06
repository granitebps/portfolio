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
                    <form action="{{route('skill.store')}}" method="post">
                      @csrf
                      <div class="form-group">
                        <label for="name">Skill Name</label>
                        <input type="text" placeholder="Skill Name" name="name" class="form-control" value="{{old('name')}}">
                      </div>
                      <div class="form-group">
                        <label for="percentage">Skill Percentage</label>
                        <input type="number" placeholder="Skill Percentage" name="percentage" class="form-control" value="{{old('percentage')}}">
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
