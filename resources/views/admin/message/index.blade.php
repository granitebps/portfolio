@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title float-left">{{$title}}</h3>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if ($message->count() > 0)
                              @foreach ($message as $key => $value)
                                  <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$value->first_name}}</td>
                                    <td>{{$value->last_name}}</td>
                                    <td>{{$value->email}}</td>
                                    <td>{{$value->phone}}</td>
                                    <td>{{$value->message}}</td>
                                    <td><a href="{{route('message.delete', ['id'=>$value->id])}}" class="btn btn-danger btn-sm">Delete</a></td>
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
