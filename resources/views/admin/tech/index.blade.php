@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title float-left">{{$title}}</h3>
                  <a href="{{route('tech.create')}}" class="btn btn-success float-right">Create</a>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Technology Name</th>
                            <th>Technology Picture</th>
                            <th colspan="2">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if ($tech->count() > 0)
                              @foreach ($tech as $key => $value)
                                  <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>
                                      <a href="{{asset('storage/images/tech/'.$value->pic)}}" class="btn btn-secondary" target="_blank">View Pic</a>
                                    </td>
                                    <td>
                                      <a href="{{route('tech.edit', ['id'=>$value->id])}}" class="btn btn-primary">Edit</a>
                                    </td>
                                    <td>
                                      <form action="{{route('tech.destroy', ['id'=>$value->id])}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                      </form>
                                    </td>
                                  </tr>
                              @endforeach
                          @else
                              <tr>
                                <td colspan="5" class="text-center text-danger">No Data Found</td>
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
