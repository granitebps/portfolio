@extends('layouts.app')

@section('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">{{$title}}</h3>
                </div>

                <div class="card-body">
                    <form action="{{route('experience.update', ['id'=>$experience->id])}}" method="post">
                      @csrf
                      @method('put')
                      <div class="form-group">
                        <label for="company">Company Name</label>
                        <input type="text" placeholder="Company Name" name="company" class="form-control" value="{{old('company') ? old('company') : $experience->company}}">
                      </div>
                      <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" placeholder='Position' name="position" class="form-control" value="{{old('position') ? old('position') : $experience->position}}">
                      </div>
                      <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="text" name="start_date" class="form-control custom-date" value="{{$experience->start_date}}" />
                      </div>
                      <div class="form-group">
                        <label for="end_date">Ended Date</label>
                        <input type="text" name="end_date" class="form-control custom-date" value="{{$experience->end_date}}" />
                      </div>
                      <div class="form-check form-group">
                        <input class="form-check-input" type="checkbox" name="current_job" value="1" id="defaultCheck1" {{$experience->current_job == 1 ? 'checked' : ''}}>
                        <label class="form-check-label" for="defaultCheck1">
                          My Current Job
                        </label>
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

@section('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

  <script>
    $('.custom-date').datepicker({
      format: "MM yyyy",
      minViewMode: 1,
      autoclose: true
    });
  </script>
@endsection