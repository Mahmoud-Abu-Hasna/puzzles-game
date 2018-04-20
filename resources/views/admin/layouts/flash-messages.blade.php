@if ($flash = session('flash_message'))
<div class="col-md-6 col-md-offset-3 alert alert-{{ session('message_type') }} alert-dismissable fade in">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <div>
            {{ $flash }}
        </div>
    </div>
@endif
