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
                <h5>Registrar Pago <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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

                        {{ Form::open(array('url' => 'payments/' . $payment->id, 'id'=>'form'), ['class'=>'form-horizontal'])}}
                         {!! Form::hidden('hdd_contract_id', $contract->id, ['id'=>'hdd_contract_id']) !!}
                        {!! Form::hidden('hdd_discount_id', null, ['id'=>'hdd_discount_id']) !!}
                        @if($payment->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                        <!-- 1ra Columna -->
                        <div class="col-sm-6">                            
                            <h2>{{ $contract->citizen->name }}, Contrato # {{ $contract->number }}</h2>

                            <div class="form-group" id="data_1">
                                <label>Fecha del Pago *</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('date', $payment->date, ['class'=>'form-control', 'type'=>'text', 'placeholder'=>'01/01/2017', 'date', 'required']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Forma de Pago *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                                    {{ Form::select('type', ['EF' => 'Efectivo', 'CH' => 'Cheque', 'TA' => 'Transferencia'], $payment->type, ['id'=>'type', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required'])}}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Observación</label><small> Máx. 400 caracteres.</small>
                                <div class="input-group m-b">
                                <span class="input-group-addon"><i class="fa fa-align-justify" aria-hidden="true"></i></span>
                                {!! Form::textarea('observation', $payment->observation, ['id'=>'observation', 'rows'=>'3', 'class'=>'form-control', 'placeholder'=>'Escriba aqui alguna observación', 'maxlength'=>'400']) !!}
                                </div>
                            </div>                                                        
                        </div>
                        <!-- /1ra Columna -->
                                                
                        <!-- 2da Columna -->
                        <div class="col-sm-6">
                            
                        <h2>Recibos pendientes</h2>

                        <!-- Listado de recibos pendientes -->  
                        @if($contract->invoices->where('status', 'P'))
                            <div class="table-responsive">
                                <table id="datatable-buttons" class="table table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="text-center">Nro</th>
                                            <th class="text-right">Monto {{ Session::get('coin') }}</th>
                                            <th class="text-center">Vencimiento</th>                          
                                        </tr>
                                    </thead>
                                    <tbody>                  
                                        @php($i=0)                        
                                        @foreach($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <div class="i-checks">
                                                {!! Form::checkbox('invoices[]', $invoice->id, null, ['id'=>'invoice['.$i.']', 'class'=>'flat', 'required']) !!}
                                                </div>
                                                {!! Form::hidden('amount',  $invoice->total , ['id'=>'amount['.$i++.']']) !!}
                                            </td>
                                            <td class="text-center"><strong>{{ $invoice->id }}</strong></td>
                                            <td class="text-right">{{ money_fmt($invoice->total) }}</td>
                                            <td class="text-center">{{ $invoice->date_limit->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <!-- /Listado de recibos pendientes -->                        
                        </div>
                        <!-- /2da Columna -->

                        <!-- 3ra Columna -->
                        <div class="col-sm-6">
                            <h2>Descuentos</h2>
                             <!-- 3ra Edad -->
                             @if($contract->citizen->age_discount())
                                <p>El ciudadano tiene <strong>{{ $contract->citizen->age }}</strong> años y aplica para el descuento.</p>
                                <div class="i-checks">
                                    <p>{!! Form::checkbox('age_discount', $age_discount->type,  false, ['id'=>'age_discount',]) !!} {{ $age_discount->description }}. {{ ($age_discount->type=='M')?money_fmt($age_discount->amount).' '.Session::get('coin'):'('.money_fmt($age_discount->percent).' %) del total a pagar' }}.</p>
                                        {!! Form::hidden('age_discount_amount',  $age_discount->amount , ['id'=>'age_discount_amount']) !!}
                                        {!! Form::hidden('age_discount_percent', $age_discount->percent , ['id'=>'age_discount_percent']) !!}
                                        {!! Form::hidden('age_discount_id', $age_discount->id , ['id'=>'age_discount_id']) !!}
                                </div>
                             @endif
                            
                        <div id='div_other_discounts' style='display:solid;'>
                            <!-- Otros Descuentos -->
                             @if($other_discounts)
                                @php($i=0)
                                @foreach($other_discounts as $discount)
                                    <div class="i-checks">
                                        <p>{!! Form::radio('other_discount[]', $discount->type,  false, ['id'=>'other_discount['.$i.']']) !!} {{ $discount->description }}. {{ ($discount->type=='M')?money_fmt($discount->amount).' '.Session::get('coin'):'('.money_fmt($discount->percent).' %) del total a pagar' }}.</p>
                                            {!! Form::hidden('other_discount_amount',  $discount->amount , ['id'=>'other_discount_amount['.$i.']']) !!}
                                            {!! Form::hidden('other_discount_percent',  $discount->percent , ['id'=>'other_discount_percent['.$i.']']) !!}
                                            {!! Form::hidden('other_discount_id',  $discount->id , ['id'=>'other_discount_id['.$i++.']']) !!}
                                    </div>                             
                                @endforeach
                            @endif
                        </div>
                        
                        </div>


                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                            <h4><label id='total_recibos'>Sub Total: </label></h4>
                            <h4><label id='total_descuento'>Descuento: </label></h4>
                            <h3><label id='total_monto'>TOTAL PAGO: </label></h3>
                        </div>

                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Pagar</button>
                                    <button type="reset" id="btn_reset" class="btn btn-sm btn-default">Reset</button>
                                    <a href="{{URL::to('payments.contracts_debt/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
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
      
      var user_id = "{{$payment->id}}";
      if( user_id == "" )
      {        
        avatar_preview = "<img style='height:150px' src='{{ url('img/avatar_default.png') }}'>";
      }else{
        avatar_preview = "<img style='height:150px' src= '{{ url('user_avatar/'.$payment->id) }}' >";
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
        
        //Datepicker fecha del contrato
        var date_input_1=$('#data_1 .input-group.date');
        date_input_1.datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            language: 'es',
        })
        if($('#data_1 .input-group.date').val() == ''){
          $('#data_1 .input-group.date').datepicker("setDate", new Date());                
        }            
        
        // Select2 
        $("#type").select2({
          language: "es",
          placeholder: "Seleccione una forma de pago",
          minimumResultsForSearch: 10,
          allowClear: false,
          width: '100%'
        });

        // iCheck
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

    
        $('#age_discount').on('ifChecked', function(event){ 
            $("input[id^='other_discount']").iCheck('uncheck'); 
            $('#div_other_discounts').hide();
        });       

        $('#age_discount').on('ifUnchecked', function(event){ 
            $('#div_other_discounts').show();
        });

        $('#age_discount').on('ifChanged', function(event){ 
            calcula_total();            
        });

        $("input[id^='invoice']").on('ifChanged', function(event){ 
            calcula_total();
        });

        $("input[id^='other_discount']").on('ifChanged', function(event){ 
            calcula_total();
        });
        
        $('#btn_reset').on('click', function(event){ 
            $("input[id^='invoice']").iCheck('uncheck');
            $("input[id^='other_discount']").iCheck('uncheck'); 
        });


    //Rutina para el calculo del monto a pagar
    function calcula_total(){
        var total=0;
        var tot_invoices=0;
        var discount=0;
        var percent=0;
        var amount=0;
        var discount_id=0;
        //Recorre los recibos seleccinados
        for (i=0; i< {{ $invoices->count() }}; i++){
            if (document.getElementById("invoice["+i+"]").checked){
                total= total + parseFloat(document.getElementById("amount["+i+"]").value);
            }
        }
        tot_invoices = total;
        discount = 0;
        //Descuento 3ra edad
        if ('{{ $contract->citizen->age_discount() }}'=='1' && document.getElementById("age_discount").checked){
            discount_id = $("#age_discount_id").val();
            //console.log ("Entro por EDAD");
            if($("#age_discount").val()=='P'){
                percent = $("#age_discount_percent").val();
                discount = total*(percent/100);
                tot_invoices = total;
                total = total - (discount);
            }else if($("#age_discount").val()=='M'){
                amount = $("#age_discount_amount").val();
                discount = amount;
                tot_invoices = total;
                total = total - discount;
            }
        //Otros Descuentos
        }else{
            //Recorre para obtener el monto y el porcentaje
            for (i=0; i< {{ $other_discounts->count() }}; i++){
                if (document.getElementById("other_discount["+i+"]").checked){
                    discount_id = document.getElementById("other_discount_id["+i+"]").value;
                    type = document.getElementById("other_discount["+i+"]").value;
                    amount  = parseFloat(document.getElementById("other_discount_amount["+i+"]").value);
                    percent = parseFloat(document.getElementById("other_discount_percent["+i+"]").value);
                }
            }
            if(type =='P'){
                discount = total*(percent/100);
                tot_invoices = total;
                total = total - (discount);
            }else if(type == 'M'){
                discount = amount;
                tot_invoices = total;
                total = total - discount;
            }        
        }
        //console.log (discount_id);
        $('#hdd_discount_id').val(discount_id);
        //tot_invoices = tot_invoices.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        //discount = discount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        //total = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        document.getElementById("total_recibos").innerHTML = "Sub Total: " + tot_invoices;
        document.getElementById("total_descuento").innerHTML = "Descuento: " + discount;
        document.getElementById("total_monto").innerHTML = "PAGO TOTAL: " + total;        
    }       
    

    });
    </script>

@endpush