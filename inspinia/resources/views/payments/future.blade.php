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
                <h5>Registrar Pago por Adelantado <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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

                        {{ Form::open(array('url' => 'payments.payment_future', 'id'=>'form'), ['class'=>'form-horizontal'])}}
                        {!! Form::hidden('hdd_contract_id', $contract->id, ['id'=>'hdd_contract_id']) !!}
                        {!! Form::hidden('hdd_flat_rate', $flat_rate->amount, ['id'=>'hdd_flat_rate']) !!}
                        {!! Form::hidden('hdd_discount_id', null, ['id'=>'hdd_discount_id']) !!}
                        {!! Form::hidden('hdd_initial_month', $initial_month , ['id'=>'hdd_initial_month']) !!}
                        {!! Form::hidden('hdd_final_month', null, ['id'=>'hdd_final_month']) !!}
                        {!! Form::hidden('hdd_year', $year, ['id'=>'hdd_year']) !!}
                        <!-- 1ra Columna -->
                        <div class="col-sm-5">                            
                            <h2>{{ $contract->citizen->name }}, Contrato # {{ $contract->number }}</h2>

                            <div class="form-group" id="data_1">
                                <label>Fecha del Pago *</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('date', $payment->date, ['class'=>'form-control', 'type'=>'date', 'placeholder'=>'01/01/2017', 'required']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Período a Pagar *</label><br>                            
                                Desde: {{ month_letter($initial_month, 'lg') }} {{ $year }}</p> Hasta:
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    <select name="final_month" id="final_month" class="form-control" required="required">
                                        @for($i=intval($initial_month); $i<=12; $i++)
                                            <option value="{{ $i }}">{{ month_letter($i, 'lg') }}</option>
                                        @endfor
                                    </select>                            
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
                        <div class="col-sm-7">
                            
                        <h2>Servicio</h2>
                            <p><i class="fa fa-info-circle"></i><small> Los pagos por adelantado se calcularán a monto fijo.</small></p>                        
                            <p><strong>Monto del Servicio:</strong> <strong>{{ money_fmt($flat_rate->amount) }} {{ Session::get('coin') }}</strong></p>
                        
                        <h2>Cargos</h2>
                        <p><i class="fa fa-info-circle"></i><small> Los cargos se calculan por cada recibo a pagar. Ej. Si usted va a pagar 3 recibos el cargo se multiplacará por 3.</small></p>
                            <!-- Cargos -->
                             @if($charges)
                                @php($i=0)
                                @foreach($charges as $charge)
                                    <div class="i-checks">
                                        <p>{!! Form::checkbox('charge[]', $charge->id,  false, ['id'=>'charge['.$i.']']) !!} {{ $charge->description }}. {{ ($charge->type=='M')?money_fmt($charge->amount).' '.Session::get('coin'):'('.money_fmt($charge->percent).' %) del total a pagar' }}.</p>
                                            {!! Form::hidden('charge_amount',  $charge->amount , ['id'=>'charge_amount['.$i.']']) !!}
                                            {!! Form::hidden('charge_percent',  $charge->percent , ['id'=>'charge_percent['.$i.']']) !!}
                                            {!! Form::hidden('charge_type[]',  $charge->type , ['id'=>'charge_type['.$i++.']']) !!}
                                    </div>                             
                                @endforeach
                            @endif
                            <!-- /Cargos -->
                            
                            <!-- IVA -->
                            <div class="i-checks">
                                    <p>{!! Form::checkbox('iva', $iva->percent,  false, ['id'=>'iva',]) !!} {{ $iva->description }}. {{ '('.money_fmt($iva->percent).' %) del total a pagar' }}.</p>
                            </div>
                             <!-- /IVA -->

                        </div>
                        <!-- /2da Columna -->

                        <!-- 3ra Columna -->
                        <div class="col-sm-6">
                            <h2>Descuento</h2>
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
                                @php($count_od=0)
                                @foreach($other_discounts as $discount)
                                    @if($discount->show_temporary())
                                        <div class="i-checks">
                                            <p>{!! Form::radio('other_discount[]', $discount->id,  false, ['id'=>'other_discount['.$count_od.']']) !!} {{ $discount->description }}. {{ ($discount->type=='M')?money_fmt($discount->amount).' '.Session::get('coin'):'('.money_fmt($discount->percent).' %) del total a pagar' }}. {!! ($discount->temporary=='Y')?'<small>(Desde '.$discount->initial_date->format('d/m/Y').' Hasta '.$discount->final_date->format('d/m/Y').')</small>':'' !!}</p>
                                            {!! Form::hidden('other_discount_amount',  $discount->amount , ['id'=>'other_discount_amount['.$count_od.']']) !!}
                                            {!! Form::hidden('other_discount_percent',  $discount->percent , ['id'=>'other_discount_percent['.$count_od.']']) !!}
                                            {!! Form::hidden('other_discount_type',  $discount->type , ['id'=>'other_discount_type['.$count_od++.']']) !!}
                                        </div>                             
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        
                        </div>


                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                            <h4><label id='servicios_cargos'>Servicio + Cargos: </label></h4>
                            <h4><label id='tot_iva'>IVA: </label></h4>
                            <h4><label id='sub_tot'>SUB TOTAL: </label></h4>                            
                            <h4><label id='total_descuento'>Descuento: </label></h4>
                            <h3><label id='total_monto'>TOTAL PAGO: </label></h3>
                        </div>

                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Pagar</button>
                                    <button type="reset" id="btn_reset" class="btn btn-sm btn-default">Reset</button>
                                    <a href="{{URL::to('payments.contracts_solvent/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
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
                
        calcula_total();

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

        $("#final_month").select2({
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

        $('#iva').on('ifChanged', function(event){ 
            calcula_total();            
        });

        $('#final_month').on('change', function(event){ 
            calcula_total();
        });

        $("input[id^='other_discount']").on('ifChanged', function(event){ 
            calcula_total();
        });
        
        $("input[id^='charge']").on('ifChanged', function(event){ 
            calcula_total();
        });

        
        $('#btn_reset').on('click', function(event){ 
            $("input[id='iva']").iCheck('uncheck');
            $("input[id^='charge']").iCheck('uncheck');
            $("input[id^='other_discount']").iCheck('uncheck');
            if ('{{ $contract->citizen->age_discount() }}'=='1'){
                $("input[id='age_discount']").iCheck('uncheck');
            } 
        });    

    //Rutina para el calculo del monto a pagar
    function calcula_total(){
        var total=0;
        var tot_invoices=0;
        var charge=0;
        var tot_charges=0;
        var sub_total=0;
        var iva=0;
        var discount=0;
        var percent=0;
        var amount=0;
        var discount_id=0;
        
        //Calcula monto total por consumo
        flat_rate =   $('#hdd_flat_rate').val();
        nro_months = $('#final_month').val() - $('#hdd_initial_month').val()+1;
        total = flat_rate*nro_months;
        tot_invoices = total;                
        //Cargos
        for (i=0; i< {{ $charges->count() }}; i++){
            if (document.getElementById("charge["+i+"]").checked){
                charge_id = document.getElementById("charge["+i+"]").value;
                type = document.getElementById("charge_type["+i+"]").value;
                amount  = parseFloat(document.getElementById("charge_amount["+i+"]").value);
                percent = parseFloat(document.getElementById("charge_percent["+i+"]").value);        
                if(type =='P'){
                    charge = total*(percent/100)*nro_months;
                }else if(type == 'M'){
                    charge = amount*nro_months;
                }        
                tot_charges = tot_charges + charge;
            }
        }
        total = total + charge;
        amount =0;        
        //iva
        if (document.getElementById("iva").checked){
            percent = $("#iva").val();
            iva = total*(percent/100);
            total = total + iva;
        }
        //Descuento 3ra edad
        if ('{{ $contract->citizen->age_discount() }}'=='1' && document.getElementById("age_discount").checked){
            discount_id = $("#age_discount_id").val();
            if($("#age_discount").val()=='P'){
                percent = $("#age_discount_percent").val();
                discount = total*(percent/100);
            }else if($("#age_discount").val()=='M'){
                amount = $("#age_discount_amount").val();
                discount = amount;
            }
        //Otros Descuentos
        }else{
            //Recorre para obtener el monto y el porcentaje
            for (i=0; i< {{ $count_od }}; i++){
                if (document.getElementById("other_discount["+i+"]").checked){
                    discount_id = document.getElementById("other_discount["+i+"]").value;
                    type = document.getElementById("other_discount_type["+i+"]").value;
                    amount  = parseFloat(document.getElementById("other_discount_amount["+i+"]").value);
                    percent = parseFloat(document.getElementById("other_discount_percent["+i+"]").value);
                    if(type =='P'){
                        discount = total*(percent/100);
                    }else if(type == 'M'){
                        discount = amount;
                    }
                }
            }
        }
        total = total - discount;

        $('#hdd_discount_id').val(discount_id);
        document.getElementById("servicios_cargos").innerHTML = "Servicio + Cargos: " +money_fmt(tot_invoices)+" + "+money_fmt(tot_charges)+ " = " +money_fmt(tot_charges+tot_invoices);
        document.getElementById("tot_iva").innerHTML = "IVA: " + money_fmt(iva);
        document.getElementById("sub_tot").innerHTML = "SUB TOTAL: " + money_fmt(tot_charges+tot_invoices+iva);
        document.getElementById("total_descuento").innerHTML = "Descuento: " + money_fmt(discount);
        document.getElementById("total_monto").innerHTML = "PAGO TOTAL: " + money_fmt(total)+ " {{ Session::get('coin') }}";        
    }
    
    function money_fmt(num){
        num_fmt = num.toFixed(2).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        return num_fmt;
        //console.log (num_fmt);
    }

    });
    </script>

@endpush