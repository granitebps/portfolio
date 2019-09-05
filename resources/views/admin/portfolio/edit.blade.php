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
                    <form action="{{route('portfolio.update', ['id'=>$portfolio->id])}}" method="post" enctype="multipart/form-data">
                      @method('put')
                      @csrf
                      <div class="form-group">
                        <label for="name">Portfolio Name</label>
                        <input type="text" placeholder="Portfolio Name" name="name" class="form-control" value="{{$portfolio->name}}">
                      </div>
                      <div class="form-group">
                        <label for="desc">Portfolio Description</label>
                        <textarea name="desc" class="form-control" cols="30" rows="5">{{$portfolio->desc}}</textarea>
                      </div>
                      <div class="form-group">
                        <button type="button" class="btn btn-secondary view" data-toggle="modal" data-target="#exampleModal" id="{{$portfolio->id}}">
                          Check for Thumbnail and Picture
                        </button>
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
                        <input type="text" placeholder="Portfolio URL" name="url" class="form-control" value="{{$portfolio->url}}">
                      </div>
                      <div class="form-group">
                        <label for="type">Portfolio Type</label>
                        <select name="type" class="form-control">
                          <option value="" disabled selected>-- Select Portfolio Type --</option>
                          <option {{$portfolio->type == 1 ? 'selected' : ''}} value="1">Personal Project</option>
                          <option {{$portfolio->type == 2 ? 'selected' : ''}} value="2">Client Project</option>
                        </select>
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