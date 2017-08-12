@extends('layouts.app')

@push('stylesheets')

@endpush

@section('page-header')
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            
            <!-- ibox-title -->
            <div class="ibox-title">
                <h5>{{ ($administration->id) ? "Modificar Administración" : "Registrar Administración" }} <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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
                        {{ Form::open(array('url' => 'administrations/' . $administration->id, 'id'=>'form'), ['class'=>'form-horizontal'])}}
                        @if($administration->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                            <div class="form-group">
                                <label>Período *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    {!! Form::text('period', $administration->period, ['id'=>'period', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. 20170601', 'maxlength'=>'100', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Autoridad *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    {!! Form::text('authority', $administration->authority, ['id'=>'authority', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Jhon Doe', 'maxlength'=>'100', 'required']) !!}
                                </div>
                            </div>                                                        
                            <div class="form-group">
                                <label>Cargo Actual *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-id-card-o" aria-hidden="true"></i></span>
                                    {!! Form::text('position', $administration->position, ['id'=>'position', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Alcalde', 'maxlength'=>'100', 'required']) !!}
                                </div>
                            </div>                                                        
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                                    <a href="{{URL::to('administrations/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
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
    });

</script>

@endpush