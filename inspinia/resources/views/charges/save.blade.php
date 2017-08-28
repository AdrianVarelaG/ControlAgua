@extends('layouts.app')

@push('stylesheets')
<!-- Fileinput -->
<link href="{{ URL::asset('js/plugins/kartik-fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />
<!-- Select2 -->
<link href="{{ URL::asset('js/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
<!-- iCheck -->
<link href="{{ URL::asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<!-- Switchery -->
<link href="{{ URL::asset('js/plugins/switchery/dist/switchery.css') }}" rel="stylesheet">

@endpush

@section('page-header')
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            
            <!-- ibox-title -->
            <div class="ibox-title">
                @if($charge->id==1)
                    <h5>Modificar Impuesto
                @else
                    <h5>{{ ($charge->id) ? "Modificar Cargo Adicional" : "Registrar Cargo Adicional" }}                 
                @endif
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
                        {{ Form::open(array('url' => 'charges/' . $charge->id, 'id'=>'form'), ['class'=>'form-horizontal'])}}
                        @if($charge->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                             <div class="form-group">
                                <label>Descripción *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i></span>
                                    {!! Form::text('description', $charge->description, ['id'=>'description', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Gastos Administrativos', 'maxlength'=>'100', 'required']) !!}
                                </div>
                            </div>                            
                        @if($charge->id !=1)
                            <div class="form-group">
                                <div class="input-group m-b">
                                    <div class="i-checks"><label> {!! Form::radio('type', 'M',  ($charge->id)?($charge->type=='M'):true, ['id'=>'type']) !!} <i></i> Monto Fijo </label></div>
                                    <div class="i-checks"><label> {!! Form::radio('type', 'P',  ($charge->id)?($charge->type=='P'):false, ['id'=>'type']) !!} <i></i> Porcentual </label></div>
                                </div>
                            </div>  
                        @endif
                        <div id='div_amount' style='display:solid;'>
                            <div class="form-group">
                                <label>Monto *</label><small> Para decimales use el punto (.)</small>
                                <div class="input-group m-b">
                                    <span class="input-group-addon">{{ Session::get('coin') }}</span>
                                    {!! Form::text('amount', $charge->amount, ['id'=>'amount', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'', 'number', 'required', 'min'=>'0']) !!}
                                </div>
                            </div>                            
                        </div>    
                        <div id='div_percent' style='display:none;'>
                            <div class="form-group">
                                <label>Porcentaje *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                    {!! Form::text('percent', $charge->percent, ['id'=>'percent', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'', 'number', 'required', 'min'=>'0', 'max'=>'100']) !!}
                                </div>
                            </div>                            
                        </div>                                
                        @if($charge->id == 1)
                            <div class="form-group">
                                <p>{!! Form::checkbox('status_iva', null,  ($charge->status=='A')?true:false, ['id'=>'status_iva', 'class'=>'js-switch']) !!}&nbsp;&nbsp;<strong>Permisología del IVA</strong><br/>
                                <p><small><strong>ON</strong> El operador podrá decidir si aplica o no el IVA al momento de la generación de los recibos.</small></p>
                                <p><small><strong>OFF</strong> El IVA siempre se aplicará. El operador no tendrá posibilidad de manipular el IVA.</small></p>
                            </div>                        
                        @endif    
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                                    @if($charge->id==1)
                                        <a href="{{URL::to('home')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                                    @else
                                        <a href="{{URL::to('charges/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
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
<!-- Switchery -->
<script src="{{ URL::asset('js/plugins/switchery/dist/switchery.js') }}"></script>


<!-- Page-Level Scripts -->
<script>
      
      var user_id = "{{$charge->id}}";
      if( user_id == "" )
      {        
        avatar_preview = "<img style='height:150px' src='{{ url('img/avatar_default.png') }}'>";
      }else{
        avatar_preview = "<img style='height:150px' src= '{{ url('user_avatar/'.$charge->id) }}' >";
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
        
        if('{{ $charge->type }}'=='M'){
          $('#div_amount').show();
          $('#div_percent').hide();            
        }else if ('{{ $charge->type }}'=='P'){
          $('#div_amount').hide();
          $('#div_percent').show();                        
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

        // Switchery
        if('{{ $charge->id }}'=='1'){
            var elem = document.querySelector('#status_iva');
            var init = new Switchery(elem, { size: 'small', color: '#1AB394' });            
        }
    
    });
    </script>

@endpush