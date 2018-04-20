@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Enigma {{ $enigma_id }} Tips</span>
                        <span style="float: right;margin-top: -4px!important;"> <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addKey">Add New Tip</button>
</span>
                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Tip Value</th>
                                <th>Tip Arabic Value</th>
                                <th>Type</th>
                                <th>Charge</th>
                                <th>Discount</th>
                                <th>Published</th>
                                <th>Tip Image</th>
                                <th>Choices</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($tips as $tip)
                                <tr>
                                    <th scope="row">{{ $tip->id }}</th>
                                    <td class="tip_value" >{{ $tip->tip_value }}</td>
                                    <td class="tip_value_ar" >{{ $tip->tip_value_ar }}</td>
                                    <td class="tip_type" >{{ $tip->type }}</td>
                                    <td class="tip_charge" >{{ $tip->charge }}</td>
                                    <td class="tip_discount" >{{ $tip->discount }}</td>
                                    <td class="tip_publish" >{{ $tip->is_published == 1 ? 'published':'Not Published' }}</td>
                                    <td class="tip_image" ><img width='100' src="{{ $tip->type == 'image' ? asset($tip->tip_value) : 'http://via.placeholder.com/140x100' }}"></td>
                                    <td>
                                        <button  class="btn btn-success btn-xs edit_button" data-toggle="tooltip" data-placement="bottom" title="edit key"  data-id="{{ $tip->id }}"><i class="fa fa-edit"></i></button>
                                        <button  class="btn btn-danger btn-xs delete_button" data-toggle="tooltip" data-placement="bottom" title="delete key" data-id="{{ $tip->id }}"><i class="fa fa-times"></i></button>
                                        <a href="/admin/tip/{{ $tip->id }}/publish" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="publish" ><i class="fa fa-user"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>

                                    <td colspan="9">No tips are Provided</td>

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


                <form class="form-horizontal" method="POST" action="{{ route('admin.postNewTip') }}" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Tip</h4>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden"  name="enigma_id" value="{{ $enigma_id }}">

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
                                <label for="tip_value_image" class="col-md-4 control-label">Tip Value Image</label>

                                <div class="col-md-6">
                                    <input id="tip_value_image" type="file" class="form-control" name="tip_value_image"  accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div id="text_type" class="hidden">
                            <div class="form-group {{ $errors->has('tip_value') ? ' has-error' : '' }}">
                                <label for="tip_value" class="col-md-4 control-label">Tip Value</label>

                                <div class="col-md-6">
                                    <input id="tip_value" type="text" class="form-control" name="tip_value" value="{{ old('tip_value') }}">

                                    @if ($errors->has('tip_value'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('tip_value') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('tip_value_ar') ? ' has-error' : '' }}">
                                <label for="tip_value_ar" class="col-md-4 control-label">Tip Arabic Value </label>

                                <div class="col-md-6">
                                    <input id="tip_value_ar" type="text" class="form-control" name="tip_value_ar" value="{{ old('tip_value_ar') }}">

                                    @if ($errors->has('tip_value_ar'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('tip_value_ar') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('charge') ? ' has-error' : '' }}">
                            <label for="charge" class="col-md-4 control-label">Charge</label>

                            <div class="col-md-6">
                                <input id="charge" type="text" class="form-control" name="charge" value="{{ old('charge') }}" required autofocus>

                                @if ($errors->has('charge'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('charge') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('discount') ? ' has-error' : '' }}">
                            <label for="discount" class="col-md-4 control-label">Discount</label>

                            <div class="col-md-6">
                                <input id="discount" type="text" class="form-control" name="discount" value="{{ old('discount') }}" required autofocus>

                                @if ($errors->has('discount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('discount') }}</strong>
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

                <form class="form-horizontal" method="POST" action="{{ route('admin.postEditTip') }}" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Tip</h4>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input id="edit_tip_id" type="hidden" name="tip_id" value="{{ old('tip_id') }}" required autofocus>

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
                                <label for="edit_tip_value_image" class="col-md-4 control-label">Tip Value Image</label>

                                <div class="col-md-6">
                                    <input id="edit_tip_value_image" type="file" class="form-control" name="tip_value_image"  accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div id="edit_text_type" class="hidden">
                            <div class="form-group {{ $errors->has('tip_value') ? ' has-error' : '' }}">
                                <label for="edit_tip_value" class="col-md-4 control-label">Tip Value</label>

                                <div class="col-md-6">
                                    <input id="edit_tip_value" type="text" class="form-control" name="tip_value" value="{{ old('tip_value') }}">

                                    @if ($errors->has('tip_value'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('tip_value') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('tip_value_ar') ? ' has-error' : '' }}">
                                <label for="edit_tip_value_ar" class="col-md-4 control-label">Tip Arabic Value </label>

                                <div class="col-md-6">
                                    <input id="edit_tip_value_ar" type="text" class="form-control" name="tip_value_ar" value="{{ old('tip_value_ar') }}">

                                    @if ($errors->has('tip_value_ar'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('tip_value_ar') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('charge') ? ' has-error' : '' }}">
                            <label for="edit_charge" class="col-md-4 control-label">Charge</label>

                            <div class="col-md-6">
                                <input id="edit_charge" type="text" class="form-control" name="charge" value="{{ old('charge') }}" required autofocus>

                                @if ($errors->has('charge'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('charge') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('discount') ? ' has-error' : '' }}">
                            <label for="edit_discount" class="col-md-4 control-label">Discount</label>

                            <div class="col-md-6">
                                <input id="edit_discount" type="text" class="form-control" name="discount" value="{{ old('discount') }}" required autofocus>

                                @if ($errors->has('discount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('discount') }}</strong>
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

                <form class="form-horizontal" method="POST" action="{{ route('admin.postDeleteTip') }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete Tip</h4>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}

                        <input id="delete_tip_id" type="hidden" name="tip_id" value="{{ old('tip_id') }}">

                        <div class="form-group ">
                            <p class="col-md-10 col-md-offset-1">
                                Are sure you want to delete this tip?
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
                var tip_value = $(this).parent().siblings('.tip_value').html();
                var tip_value_ar = $(this).parent().siblings('.tip_value_ar').html();
                var tip_type = $(this).parent().siblings('.tip_type').html();
                var tip_charge = $(this).parent().siblings('.tip_charge').html();
                var tip_discount = $(this).parent().siblings('.tip_discount').html();
                $('#edit_tip_id').val(id);
                $('#edit_type').val(tip_type);
                if(tip_type == 'image'){

                    $('#edit_image_type').removeClass('hidden');
                    $('#edit_text_type').addClass('hidden');
                }else{
                    $('#edit_image_type').addClass('hidden');
                    $('#edit_text_type').removeClass('hidden');
                    $('#edit_tip_value').val(tip_value);
                    $('#edit_tip_value_ar').val(tip_value_ar);
                }

                $('#edit_charge').val(tip_charge);
                $('#edit_discount').val(tip_discount);
                $('#editKey').modal();

            });
            $('.delete_button').click(function(){
                var id = $(this).data('id');
                $('#delete_tip_id').val(id);
                $('#deleteKey').modal();

            });

        });
    </script>
@endsection