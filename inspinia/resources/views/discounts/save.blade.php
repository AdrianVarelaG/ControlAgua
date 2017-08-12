@extends('layouts.app')

@push('stylesheets')
<!-- Fileinput -->
<link href="{{ URL::asset('js/plugins/kartik-fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />
<!-- Select2 -->
<link href="{{ URL::asset('js/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
<!-- iCheck -->
<link href="{{ URL::asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<!-- DatePicker -->
<link href="{{ URL::asset('css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">

@endpush

@section('page-header')
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            
            <!-- ibox-title -->
            <div class="ibox-title">
                    <h5>{{ ($discount->id) ? "Modificar Descuento" : "Registrar Descuento" }}
                    <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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
                        {{ Form::open(array('url' => 'discounts/' . $discount->id, 'id'=>'form'), ['class'=>'form-horizontal'])}}
                        @if($discount->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                        
                        @if($discount->id == 1)
                        
                            <div class="form-group">
                                <label>Edad *</label><small> Edad mínima para optar al descuento.</small>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    {!! Form::text('age', $discount->age, ['id'=>'age', 'class'=>'form-control', 'type'=>'number', 'placeholder'=>'Ej. Gastos Administrativos', 'min'=>'0', 'max'=>'100', 'required']) !!}
                                </div>
                            </div>                                                
                        
                        @else
                        
                        <div class="form-group">
                            <div class="i-checks">
                                <label>{!! Form::checkbox('temporary', null,  ($discount->temporary=='Y')?true:false, ['id'=>'temporary']) !!} Descuento Temporal.</label>
                            </div>
                        </div>                                                    
                        <div id='div_dates' style='display:none;'>
                            <div class="form-group" id="data_1">
                                <label>Desde *</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('initial_date', ($discount->initial_date)?$discount->initial_date->format('d/m/Y'):'', ['class'=>'form-control', 'type'=>'text', 'placeholder'=>'01/01/2017', 'date', 'required']) }}
                                </div>
                            </div>                            
                            <div class="form-group" id="data_2">
                                <label>Hasta *</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('final_date', ($discount->final_date)?$discount->final_date->format('d/m/Y'):'', ['class'=>'form-control', 'type'=>'text', 'placeholder'=>'01/01/2017', 'date', 'required']) }}
                                </div>
                            </div>
                        </div>                                                    
                        @endif
                            <div class="form-group">
                                <label>Descripción *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    {!! Form::text('description', $discount->description, ['id'=>'description', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Gastos Administrativos', 'maxlength'=>'100', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <div class="i-checks"><label> {!! Form::radio('type', 'M',  ($discount->id)?($discount->type=='M'):true, ['id'=>'type']) !!} <i></i> Monto Fijo </label></div>
                                    <div class="i-checks"><label> {!! Form::radio('type', 'P',  ($discount->id)?($discount->type=='P'):false, ['id'=>'type']) !!} <i></i> Porcentual </label></div>
                                </div>
                            </div>  
                        <div id='div_amount' style='display:solid;'>
                            <div class="form-group">
                                <label>Monto *</label><small> Para decimales use el punto (.)</small>
                                <div class="input-group m-b">
                                    <span class="input-group-addon">{{ Session::get('coin') }}</span>
                                    {!! Form::text('amount', $discount->amount, ['id'=>'amount', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'', 'number', 'required', 'min'=>'0']) !!}
                                </div>
                            </div>                            
                        </div>    
                        <div id='div_percent' style='display:none;'>
                            <div class="form-group">
                                <label>Porcentaje *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                    {!! Form::text('percent', $discount->percent, ['id'=>'percent', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'', 'number', 'required', 'min'=>'0', 'max'=>'100']) !!}
                                </div>
                            </div>                            
                        </div>                                
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                                    @if($discount->id==1)
                                        <a href="{{URL::to('home')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                                    @else
                                        <a href="{{URL::to('discounts/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                                    @endif
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
<!-- Fileinput -->
<script src="{{ URL::asset('js/plugins/kartik-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/kartik-fileinput/js/fileinput_locale_es.js') }}"></script>
<!-- Select2 -->
<script src="{{ URL::asset('js/plugins/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/select2/dist/js/i18n/es.js') }}"></script>
<!-- iCheck -->
<script src="{{ URL::asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<!-- DatePicker --> 
<script src="{{ URL::asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::asset('js/plugins/datapicker/bootstrap-datepicker.es.min.js') }}"></script>


<!-- Page-Level Scripts -->
<script>
      
      var user_id = "{{$discount->id}}";
      if( user_id == "" )
      {        
        avatar_preview = "<img style='height:150px' src='{{ url('img/avatar_default.png') }}'>";
      }else{
        avatar_preview = "<img style='height:150px' src= '{{ url('user_avatar/'.$discount->id) }}' >";
      }
      
      // Fileinput    
      $('#avatar').fileinput({
        language: 'es',
        allowedFileExtensions : ['jpg', 'jpeg', 'png'],
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
        showUpload: false,        
        maxFileSize: 2000,
        maxFilesNum: 1,
        overwriteInitial: true,
        progressClass: true,
        progressCompleteClass: true,
        initialPreview: [
          avatar_preview
        ]      
      });            
    
    $(document).ready(function() {
                
        // Validation
        $("#form").validate({
            submitHandler: function(form) {
                $("#btn_submit").attr("disabled",true);
                form.submit();
            }        
        });
        
        //Seteo al actualizar
        if('{{ $discount->type }}'=='M'){
          $('#div_amount').show();
          $('#div_percent').hide();            
        }else if ('{{ $discount->type }}'=='P'){
          $('#div_amount').hide();
          $('#div_percent').show();                        
        }

        if('{{ $discount->temporary }}'=='Y'){
          $('#div_dates').show();
        }
        
        // iCheck
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        $('#type').on('ifChecked', function(event){ 
          $('#div_amount').show();
          $('#div_percent').hide();
        });       

        $('#type').on('ifUnchecked', function(event){ 
          $('#div_amount').hide();
          $('#div_percent').show();
        });       

        $('#temporary').on('ifChecked', function(event){ 
          $('#div_dates').show();
        });       

        $('#temporary').on('ifUnchecked', function(event){ 
          $('#div_dates').hide();
        });       

        //Datepicker fechas del descuento
        var date_input_1=$('#data_1 .input-group.date');
        date_input_1.datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            language: 'es',
        })
        if('{{ $discount->initial_date }}' == ''){
          $('#data_1 .input-group.date').datepicker("setDate", new Date());                
        }            
    
        var date_input_1=$('#data_2 .input-group.date');
        date_input_1.datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            language: 'es',
        })

    });
    </script>

@endpush