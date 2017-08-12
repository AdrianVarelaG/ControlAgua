<!-- show erros -->
@if (count($errors) > 0)
  <div class="col-md-12 col-sm-12 col-xs-12 form-group">
    <div class="alert alert-danger fade in">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <i class="fa fa-exclamation-triangle"></i> <strong>Disculpe!</strong>
      <ul>
        @foreach ($errors->all() as $error)
          <li>{!! $error !!}</li>
        @endforeach
      </ul>
    </div>
  </div>
@endif
<!-- show erros -->