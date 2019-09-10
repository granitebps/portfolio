@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title float-left">{{$title}}</h3>
                  <a href="{{route('portfolio.create')}}" class="btn btn-success float-right">Create</a>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Portfolio Name</th>
                            <th>Portfolio Picture</th>
                            <th>Portfolio Description</th>
                            <th>Portfolio Type</th>
                            <th>Portfolio URL</th>
                            <th colspan="2">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if ($portfolio->count() > 0)
                              @foreach ($portfolio as $key => $value)
                                  <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>
                                      <button type="button" class="btn btn-secondary view" data-toggle="modal" data-target="#exampleModal" id="{{$value->id}}">
                                        Preview
                                      </button>
                                    </td>
                                    <td>{{$value->desc}}</td>
                                    <td>
                                      @if ($value->type == 1)
                                          Personal Project
                                      @else
                                          Client Project
                                      @endif
                                    </td>
                                    <td>
                                      <a href="{{$value->url}}" target="_blank" class="btn btn-warning">Link</a>
                                    </td>
                                    <td>
                                      <a href="{{route('portfolio.edit', ['id'=>$value->id])}}" class="btn btn-primary">Edit</a>
                                    </td>
                                    <td>
                                      <form action="{{route('portfolio.destroy', ['id'=>$value->id])}}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                      </form>
                                    </td>
                                  </tr>
                              @endforeach
                          @else
                              <tr>
                                <td colspan="7" class="text-center text-danger">No Data Found</td>
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h3 class="modal-title text-center" id="exampleModalLabel">Preview Portfolio</h3>
          </div>
          <div class="modal-body">
              <div id="preview"></div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
              {{-- <button type="button" class="btn btn-primary">Lanjutkan</button> --}}
          </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script>
	$(function() {
		$('.view').click(function(){  
			var id = $(this).attr("id");
			console.log('test');
			$('#preview').hide();
			$.ajax({  
				url:"/preview",  
				method:"GET",  
				data:{id:id},  
				success:function(data){  
					$('#preview').html(data);
					$('#preview').show();
				}  
			});  
		});
	});
</script>
@endsection