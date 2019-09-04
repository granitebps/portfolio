@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title float-left">{{$title}}</h3>
                  <a href="{{route('skill.create')}}" class="btn btn-success float-right">Create</a>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Skill Name</th>
                            <th>Skill Percentage</th>
                            <th colspan="2">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if ($skill->count() > 0)
                              @foreach ($skill as $key => $value)
                                  <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->percentage}}%</td>
                                    <td>
                                      <a href="{{route('skill.edit', ['id'=>$value->id])}}" class="btn btn-primary">Edit</a>
                                    </td>
                                    <td>
                                      <form action="{{route('skill.destroy', ['id'=>$value->id])}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                      </form>
                                    </td>
                                  </tr>
                              @endforeach
                          @else
                              <tr>
                                <td colspan="4" class="text-center text-danger">No Data Found</td>
                              </tr>
                          @endif
                        </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
