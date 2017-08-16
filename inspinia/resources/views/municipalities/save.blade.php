@extends('layouts.app')

@push('stylesheets')
<!-- Select2 -->
<link href="{{ URL::asset('js/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">

@endpush

@section('page-header')
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            
            <!-- ibox-title -->
            <div class="ibox-title">
                <h5>{{ ($municipality->id) ? "Modificar Estado" : "Registrar Estado" }} <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
                <div class="ibox-tools">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-wrench"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <!-- /ibox-title -->
            
            <!-- ibox-content -->
            <div class="ibox-content">

            @include('partials.errors')

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        {{ Form::open(array('url' => 'municipalities/' . $municipality->id, 'id'=>'form'), ['class'=>'form-horizontal'])}}
                        @if($municipality->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                            <div class="form-group">
                                <label>Estado *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {{ Form::select('state', $states, $municipality->state_id, ['id'=>'state', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required'])}}
                                </div>
                            </div>                             
                             <div class="form-group">
                                <label>Nombre del Municipio *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {!! Form::text('name', $municipality->name, ['id'=>'name', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Mazatlán', 'maxlength'=>'100', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                                    <a href="{{URL::to('municipalities/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                                </div>
                            </div>                            
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <!-- /ibox-content -->
            
        </div>
    </div>
</div>
@endsection

@push('scripts')    
<!-- Select2 -->
<script src="{{ URL::asset('js/plugins/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/select2/dist/js/i18n/es.js') }}"></script>

<!-- Page-Level Scripts -->
<script>
          
    $(document).ready(function() {
                
        // Validation
        $("#form").validate({
            submitHandler: function(form) {
                $("#btn_submit").attr("disabled",true);
                form.submit();
            }        
        });
        
        // Select2 
        $("#state").select2({
          language: "es",
          placeholder: "Seleccione un estado",
          minimumResultsForSearch: 10,
          allowClear: false,
          width: '100%'
        });
    });
    </script>

@endpush