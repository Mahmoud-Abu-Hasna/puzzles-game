@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Enigmas</span>
                        <span style="float: right;margin-top: -4px!important;"> <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addKey">Add New Enigma</button>
</span>
                    </div>

                    <div class="panel-body">


                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Enigma Value</th>
                                <th>Enigma Arabic Value</th>
                                <th>Type</th>
                                <th>Correct Answer</th>
                                <th>Prize</th>
                                <th>Published</th>
                                <th>Enigma Image</th>
                                <th>Choices</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($enigmas as $enigma)
                                <tr>
                                    <th scope="row">{{ $enigma->id }}</th>
                                    <td class="enigma_value" >{{ $enigma->enigma_value }}</td>
                                    <td class="enigma_value_ar" >{{ $enigma->enigma_value_ar }}</td>
                                    <td class="enigma_type" >{{ $enigma->type }}</td>
                                    <td class="enigma_correct_answer" >{{ $enigma->correct_answer }}</td>
                                    <td class="enigma_prize" >{{ $enigma->prize }}</td>
                                    <td class="enigma_publish" >{{ $enigma->is_published == 1 ? 'published':'Not Published' }}</td>
                                    <td class="enigma_image" ><img  width='100' src="{{ $enigma->type == 'image' ? asset($enigma->enigma_value) : 'http://via.placeholder.com/140x100' }}"></td>
                                    <td>
                                        <button  class="btn btn-success btn-xs edit_button" data-toggle="tooltip" data-placement="bottom" title="edit key"  data-id="{{ $enigma->id }}"><i class="fa fa-edit"></i></button>
                                        <button  class="btn btn-danger btn-xs delete_button" data-toggle="tooltip" data-placement="bottom" title="delete key" data-id="{{ $enigma->id }}"><i class="fa fa-times"></i></button>
                                        <a href="/admin/enigma/{{ $enigma->id }}/tips"  class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="bottom" title="tips" ><i class="fa fa-tags"></i></a>
                                        <a href="/admin/enigma/{{ $enigma->id }}/publish" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="publish" ><i class="fa fa-user"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>

                                    <td colspan="9">No enigmas are Provided</td>

                                </tr>

                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add key Modal -->
    <div id="addKey" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">


                <form class="form-horizontal" method="POST" action="{{ route('admin.postNewEnigma') }}" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Enigma</h4>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class=" col-md-4 control-label">Type</label>
                            <div class="col-md-6">
                            <select id="type"
                                    name="type"
                                    class="form-control select2">
                                <option value="">choose</option>
                                <option value="image" >Image
                                </option>
                                <option value="text">Text
                                </option>
                            </select>
                        </div>
                        </div>
                        <div id="image_type" class="hidden">
                            <div class="form-group">
                                <label for="enigma_value_image" class="col-md-4 control-label">Enigma Value Image</label>

                                <div class="col-md-6">
                                    <input id="enigma_value_image" type="file" class="form-control" name="enigma_value_image" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div id="text_type" class="hidden">
                            <div class="form-group {{ $errors->has('enigma_value') ? ' has-error' : '' }}">
                                <label for="enigma_value" class="col-md-4 control-label">Enigma Value</label>

                                <div class="col-md-6">
                                    <input id="enigma_value" type="text" class="form-control" name="enigma_value" value="{{ old('enigma_value') }}">

                                    @if ($errors->has('enigma_value'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('enigma_value') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('enigma_value_ar') ? ' has-error' : '' }}">
                                <label for="enigma_value_ar" class="col-md-4 control-label">Enigma Arabic Value </label>

                                <div class="col-md-6">
                                    <input id="enigma_value_ar" type="text" class="form-control" name="enigma_value_ar" value="{{ old('enigma_value_ar') }}">

                                    @if ($errors->has('enigma_value_ar'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('enigma_value_ar') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('correct_answer') ? ' has-error' : '' }}">
                            <label for="correct_answer" class="col-md-4 control-label">Correct Answer</label>

                            <div class="col-md-6">
                                <input id="correct_answer" type="text" class="form-control" name="correct_answer" value="{{ old('correct_answer') }}" required autofocus>

                                @if ($errors->has('correct_answer'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('correct_answer') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('prize') ? ' has-error' : '' }}">
                            <label for="prize" class="col-md-4 control-label">Prize</label>

                            <div class="col-md-6">
                                <input id="prize" type="text" class="form-control" name="prize" value="{{ old('prize') }}" required autofocus>

                                @if ($errors->has('prize'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('prize') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>


            </div>

        </div>
    </div>

    <!-- Edit key Modal -->
    <div id="editKey" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">

                <form class="form-horizontal" method="POST" action="{{ route('admin.postEditEnigma') }}" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Enigma</h4>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input id="edit_enigma_id" type="hidden" name="enigma_id" value="{{ old('enigma_id') }}">

                        <div class="form-group">
                            <label class=" col-md-4 control-label">Type</label>
                            <div class="col-md-6">

                                <select id="edit_type"
                                        name="type"
                                        class="form-control select2">

                                    <option value="">select</option>
                                    <option value="image" >Image
                                    </option>
                                    <option value="text">Text
                                    </option>
                                </select>

                            </div>

                        </div>
                        <div id="edit_image_type" class="hidden">
                            <div class="form-group">
                                <label for="edit_enigma_value_image" class="col-md-4 control-label">Enigma Value Image</label>

                                <div class="col-md-6">
                                    <input id="edit_enigma_value_image" type="file" class="form-control" name="enigma_value_image"  accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div id="edit_text_type" class="hidden">
                            <div class="form-group {{ $errors->has('enigma_value') ? ' has-error' : '' }}">
                                <label for="edit_enigma_value" class="col-md-4 control-label">Enigma Value</label>

                                <div class="col-md-6">
                                    <input id="edit_enigma_value" type="text" class="form-control" name="enigma_value" value="{{ old('enigma_value') }}">

                                    @if ($errors->has('enigma_value'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('enigma_value') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('enigma_value_ar') ? ' has-error' : '' }}">
                                <label for="edit_enigma_value_ar" class="col-md-4 control-label">Enigma Arabic Value </label>

                                <div class="col-md-6">
                                    <input id="edit_enigma_value_ar" type="text" class="form-control" name="enigma_value_ar" value="{{ old('enigma_value_ar') }}">

                                    @if ($errors->has('enigma_value_ar'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('enigma_value_ar') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('correct_answer') ? ' has-error' : '' }}">
                            <label for="edit_correct_answer" class="col-md-4 control-label">Correct Answer</label>

                            <div class="col-md-6">
                                <input id="edit_correct_answer" type="text" class="form-control" name="correct_answer" value="{{ old('correct_answer') }}" required autofocus>

                                @if ($errors->has('correct_answer'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('correct_answer') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('prize') ? ' has-error' : '' }}">
                            <label for="edit_prize" class="col-md-4 control-label">Prize</label>

                            <div class="col-md-6">
                                <input id="edit_prize" type="text" class="form-control" name="prize" value="{{ old('prize') }}" required autofocus>

                                @if ($errors->has('prize'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('prize') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>

        </div>
    </div>

    <div id="deleteKey" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">

                <form class="form-horizontal" method="POST" action="{{ route('admin.postDeleteEnigma') }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete Enigma</h4>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}

                        <input id="delete_enigma_id" type="hidden" name="enigma_id" value="{{ old('enigma_id') }}">

                        <div class="form-group ">
                            <p class="col-md-10 col-md-offset-1">
                                Are sure you want to delete this enigma?
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">
                            Delete
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function()
        {
            $('#type').change(function(){
                if($(this).val() == 'image'){
                    $('#image_type').removeClass('hidden');
                    $('#text_type').addClass('hidden');
                }else{
                    $('#text_type').removeClass('hidden');
                    $('#image_type').addClass('hidden');
                }
            });
            $('#edit_type').change(function(){
                if($(this).val() == 'image'){
                    $('#edit_image_type').removeClass('hidden');
                    $('#edit_text_type').addClass('hidden');
                }else{
                    $('#edit_image_type').addClass('hidden');
                    $('#edit_text_type').removeClass('hidden');
                }
            });
            $('.edit_button').click(function(){
                var id = $(this).data('id');
                var enigma_value = $(this).parent().siblings('.enigma_value').html();
                var enigma_value_ar = $(this).parent().siblings('.enigma_value_ar').html();
                var enigma_type = $(this).parent().siblings('.enigma_type').html();
                var enigma_correct_answer = $(this).parent().siblings('.enigma_correct_answer').html();
                var enigma_prize = $(this).parent().siblings('.enigma_prize').html();
                $('#edit_enigma_id').val(id);
                $('#edit_type').val(enigma_type);
                if(enigma_type == 'image'){

                    $('#edit_image_type').removeClass('hidden');
                    $('#edit_text_type').addClass('hidden');
                }else{
                    $('#edit_image_type').addClass('hidden');
                    $('#edit_text_type').removeClass('hidden');
                    $('#edit_enigma_value').val(enigma_value);
                    $('#edit_enigma_value_ar').val(enigma_value_ar);
                }

                $('#edit_correct_answer').val(enigma_correct_answer);
                $('#edit_prize').val(enigma_prize);
                $('#editKey').modal();

            });
            $('.delete_button').click(function(){
                var id = $(this).data('id');
                $('#delete_enigma_id').val(id);
                $('#deleteKey').modal();

            });

        });
    </script>
@endsection