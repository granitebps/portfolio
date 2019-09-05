@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title float-left">{{$title}}</h3>
                  <a href="{{route('experience.create')}}" class="btn btn-success float-right">Create</a>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Company Name</th>
                            <th>Position</th>
                            <th>Start Date</th>
                            <th>Ended Date</th>
                            <th colspan="2">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if ($experience->count() > 0)
                              @foreach ($experience as $key => $value)
                                  <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$value->company}}</td>
                                    <td>{{$value->position}}</td>
                                    <td>{{$value->start_date}}</td>
                                    <td>
                                      @if ($value->current_job == 1)
                                        Now
                                      @else
                                        {{$value->end_date}}
                                      @endif
                                    </td>
                                    <td>
                                      <a href="{{route('experience.edit', ['id'=>$value->id])}}" class="btn btn-primary">Edit</a>
                                    </td>
                                    <td>
                                      <form action="{{route('experience.destroy', ['id'=>$value->id])}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                      </form>
                                    </td>
                                  </tr>
                              @endforeach
                          @else
                              <tr>
                                <td colspan="6" class="text-center text-danger">No Data Found</td>
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
