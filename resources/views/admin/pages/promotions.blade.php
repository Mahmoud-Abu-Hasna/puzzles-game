@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Promotions</span>
                        <span style="float: right;margin-top: -4px!important;"> <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addKey">Add New Promotion</button>
</span>
                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Promotion Value</th>
                                <th>Condition</th>
                                <th>Promotion Code</th>
                                <th>Activated</th>
                                <th>Choices</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($promotions as $promotion)
                                <tr>
                                    <th scope="row">{{ $promotion->id }}</th>
                                    <td class="promotion_value" >{{ $promotion->promotion_value }}</td>
                                    <td class="condition" >{{ $promotion->condition }}</td>
                                    <td class="promotion_code" >{{ $promotion->promotion_code }}</td>
                                    <td class="is_applied" >{{ $promotion->is_applied == 1 ? 'Activated':'Deactivated' }}</td>
                                    <td>
                                        <button  class="btn btn-success btn-xs edit_button" data-toggle="tooltip" data-placement="bottom" title="edit key"  data-id="{{ $promotion->id }}"><i class="fa fa-edit"></i></button>
                                        <button  class="btn btn-danger btn-xs delete_button" data-toggle="tooltip" data-placement="bottom" title="delete key" data-id="{{ $promotion->id }}"><i class="fa fa-times"></i></button>
                                        <a href="/admin/promotion/{{ $promotion->id }}/activate" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="publish" ><i class="fa fa-user"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>

                                    <td colspan="6">No promotions are Provided</td>

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


                <form class="form-horizontal" method="POST" action="{{ route('admin.postNewPromotion') }}" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New Promotion</h4>
                    </div>
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('promotion_value') ? ' has-error' : '' }}">
                            <label for="promotion_value" class="col-md-4 control-label">Value</label>

                            <div class="col-md-6">
                                <input id="promotion_value" type="text" class="form-control" name="promotion_value" value="{{ old('promotion_value') }}" required autofocus>

                                @if ($errors->has('promotion_value'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('promotion_value') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('promotion_code') ? ' has-error' : '' }}">
                            <label for="promotion_code" class="col-md-4 control-label">Code</label>

                            <div class="col-md-6">
                                <input id="promotion_code" type="text" class="form-control" name="promotion_code" value="{{ old('promotion_code') }}" required autofocus>

                                @if ($errors->has('promotion_code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('promotion_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('condition') ? ' has-error' : '' }}">
                            <label for="condition" class="col-md-4 control-label">Condition</label>

                            <div class="col-md-6">
                                <input id="condition" type="text" class="form-control" name="condition" value="{{ old('condition') }}" required autofocus>

                                @if ($errors->has('condition'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('condition') }}</strong>
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

                <form class="form-horizontal" method="POST" action="{{ route('admin.postEditPromotion') }}" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Promotion</h4>
                    </div>
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <input id="edit_promotion_id" type="hidden" name="promotion_id" value="{{ old('promotion_id') }}" required autofocus>

                        <div class="form-group{{ $errors->has('promotion_value') ? ' has-error' : '' }}">
                            <label for="edit_promotion_value" class="col-md-4 control-label">Value</label>

                            <div class="col-md-6">
                                <input id="edit_promotion_value" type="text" class="form-control" name="promotion_value" value="{{ old('promotion_value') }}" required autofocus>

                                @if ($errors->has('promotion_value'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('promotion_value') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('promotion_code') ? ' has-error' : '' }}">
                            <label for="edit_promotion_code" class="col-md-4 control-label">Code</label>

                            <div class="col-md-6">
                                <input id="edit_promotion_code" type="text" class="form-control" name="promotion_code" value="{{ old('promotion_code') }}" required autofocus>

                                @if ($errors->has('promotion_code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('promotion_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('condition') ? ' has-error' : '' }}">
                            <label for="edit_condition" class="col-md-4 control-label">Condition</label>

                            <div class="col-md-6">
                                <input id="edit_condition" type="text" class="form-control" name="condition" value="{{ old('condition') }}" required autofocus>

                                @if ($errors->has('condition'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('condition') }}</strong>
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

                <form class="form-horizontal" method="POST" action="{{ route('admin.postDeletePromotion') }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete Promotion</h4>
                    </div>
                    <div class="modal-body">
                        {!! csrf_field() !!}

                        <input id="delete_promotion_id" type="hidden" name="promotion_id" value="{{ old('promotion_id') }}" required autofocus>

                        <div class="form-group ">
                            <p class="col-md-10 col-md-offset-1">
                                Are sure you want to delete this Promotion?
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

            $('.edit_button').click(function(){
                var id = $(this).data('id');
                var promotion_value = $(this).parent().siblings('.promotion_value').html();
                var condition = $(this).parent().siblings('.condition').html();
                var promotion_code = $(this).parent().siblings('.promotion_code').html();
                $('#edit_promotion_id').val(id);
                $('#edit_promotion_value').val(promotion_value);
                $('#edit_condition').val(condition);
                $('#edit_promotion_code').val(promotion_code);
                $('#editKey').modal();

            });
            $('.delete_button').click(function(){
                var id = $(this).data('id');
                $('#delete_promotion_id').val(id);
                $('#deleteKey').modal();

            });

        });
    </script>
@endsection