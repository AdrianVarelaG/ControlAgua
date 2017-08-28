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
<!-- Animate -->
<link href="{{ URL::asset('css/animate.css') }}" rel="stylesheet">
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
                        {!! Form::hidden('hdd_discount_id', 0, ['id'=>'hdd_discount_id']) !!}
                        {!! Form::hidden('hdd_net_debt', $contract->balance, ['id'=>'hdd_net_debt']) !!}
                        @if($payment->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                    
                    <!-- 1 Row -->
                    <div class="col-md-12 col-sm-12 col-xs-12">                                                
                        <!-- 1 Column -->
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
                        <!-- 2 Column -->
                        <div class="col-sm-7">
                            <h2>Estado de Cuenta al {{ $today->format('d/m/Y') }}</h2>
                                <h3>Movimientos del Mes Anterior</h3>
                                <p>Saldo inicial: <strong>{{ money_fmt($previous_balance_m2) }} {{ Session::get('coin') }}</strong></p>
                                <small>Cargos: {{ $credits_m1->sum('amount') }}</small><br/>
                                <small>Pagos y Descuentos: {{ ($debits_m1->count())?$debits_m1->sum('amount'):'0,00' }} {{ Session::get('coin') }}</small><br/><br/>
                                <p>Total Saldo Anterior: <strong>{{ money_fmt($previous_balance_m1) }} {{ Session::get('coin') }}</strong></p>
                            
                            <h3>Movimientos del Mes Actual</h3>
                            <div class="col-sm-5">
                                <small>Cargos: {{ $credits->sum('amount') }} {{ Session::get('coin') }}</small><br/>
                                <small>Pagos y Descuentos: {{ ($debits->count())?$debits->sum('amount'):'0,00' }} {{ Session::get('coin') }}</small><br/><br/>
                                <p>Total Saldo Actual: <strong>{{ money_fmt($contract->balance) }} {{ Session::get('coin') }}</strong></p>
                            </div>                                
                            <div class="col-sm-7">
                                <small>Detalle Cargos:
                                     @foreach($credits->get() as $credit)
                                        <ul>
                                        @foreach($credit->invoice->invoice_details as $detail)
                                            <li>{{ $detail->description }} {{ money_fmt($detail->sub_total) }} {{ Session::get('coin') }}</li>
                                        @endforeach
                                        </ul>
                                    @endforeach
                                </small>
                            </div>
                        </div>
                    </div>
                    <!-- /1 Row -->

                    <!-- 2dn Row -->
                    <div class="col-md-12 col-sm-12 col-xs-12">                    
                        <!-- 1 Column -->
                        <div class="col-sm-5">    
                            <h2>Descuentos</h2>
                             <!-- 3ra Edad -->
                             @if($contract->citizen->age_discount())
                                <p>El ciudadano tiene <strong>{{ $contract->citizen->age }}</strong> años y aplica para el descuento de 3ra edad. <small>{{ ($age_discount->type=='M')?money_fmt($age_discount->amount).' '.Session::get('coin'):'('.money_fmt($age_discount->percent).' %) del total a pagar' }}.</small></p>
                                <div class="i-checks">
                                    <p>{!! Form::checkbox('age_discount', $age_discount->type,  false, ['id'=>'age_discount',]) !!} <strong>Posee credencial de la 3ra edad ?</strong></p>
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
                                            <p>{!! Form::radio('other_discount[]', $discount->type,  false, ['id'=>'other_discount['.$count_od.']']) !!} {{ $discount->description }}. {{ ($discount->type=='M')?money_fmt($discount->amount).' '.Session::get('coin'):'('.money_fmt($discount->percent).' %) del total a pagar' }}. {!! ($discount->temporary=='Y')?'<small>(Desde '.$discount->initial_date->format('d/m/Y').' Hasta '.$discount->final_date->format('d/m/Y').')</small>':'' !!}</p>
                                            {!! Form::hidden('other_discount_amount',  $discount->amount , ['id'=>'other_discount_amount['.$count_od.']']) !!}
                                            {!! Form::hidden('other_discount_percent',  $discount->percent , ['id'=>'other_discount_percent['.$count_od.']']) !!}
                                            {!! Form::hidden('other_discount_id',  $discount->id , ['id'=>'other_discount_id['.$count_od++.']']) !!}
                                        </div>                             
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        </div>
                        <!-- 1 Column -->
                        <div class="col-sm-7">
                        <!-- Resumen a Pagar -->
                            <div class="panel panel-primary">
                            <div class="panel-heading">Seleccione el monto a pagar <strong>({{ Session::get('coin') }})</strong></div>
                                <div class="panel-body">
                        <table class="table table-responsive table-hover">
                            <tbody>
                                <tr>
                                    <td width="10%">
                                        <div class="i-checks">
                                            {!! Form::radio('select_amount', 'total', true, ['id'=>'select_amount']) !!}
                                        </div>                                        
                                    </td>
                                    <td width="60%">
                                        <p><strong>TOTAL A PAGAR:</strong></p>
                                        <p id='total_desglose'>Total Saldo Actual - Descuento ({{ money_fmt($contract->balance) }} - 0,00)</p>
                                    </td>
                                    <td width="30%" class="text-center">
                                        <h3 id='total_monto'>{{ money_fmt($contract->balance) }} {{ Session::get('coin') }}</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%">
                                        <div class="i-checks">
                                            {!! Form::radio('select_amount', 'other', false, ['id'=>'select_amount']) !!}
                                        </div>
                                    </td>
                                    <td width="60%"><strong>OTRO MONTO</strong></td>
                                    <td width="30%" class="text-center">
                                    {!! Form::text('other_amount', null, ['id'=>'other_amount', 'class'=>'form-control', 'type'=>'number', 'min'=>'0' ,'placeholder'=>'', 'required', 'disabled']) !!}
                                    </td>
                                </tr>                            
                            </tbody>
                        </table>                            
                                    

                                </div>
                            </div>
                        <!-- /Resumen a Pagar -->
                    </div>
                </div>
                <!-- /2dn Row -->



                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Pagar</button>
                                    <button type="reset" id="btn_reset" class="btn btn-sm btn-default">Reset</button>
                                    <a href="{{URL::to('payments.contracts_debt/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                                </div>
                    </div>
                </div>                                                
                        

                        {{ Form::close() }}

                    </div>
                </div>
            </div>
            <!-- /ibox-content -->
            
            <!-- Invoice Details MODAL-->
            <div class="modal inmodal" id="myModal4" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content animated fadeIn">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">Detalle Deuda</h4>
                            <h4>Recibos por Cancelar</h4>
                        </div>
                        <div class="modal-body">
                        @if($contract->invoices->where('status', 'P'))
                            <div class="table-responsive">
                                <table id="datatable-buttons" class="table table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Facturación</th>
                                            <th class="text-center">Vencimiento</th>
                                            <th class="text-center">Estatus</th>
                                            <th class="text-right">Monto {{ Session::get('coin') }}</th>          
                                        </tr>
                                    </thead>
                                    <tbody>                  
                                        @foreach($invoices as $invoice)
                                        <tr>
                                            <td class="text-center"><strong>{{ $invoice->id }}</strong></td>
                                            <td class="text-center">{{ $invoice->month }}/{{ $invoice->year }}</td>
                                            <td class="text-center">{{ $invoice->date_limit->format('d/m/Y') }}
                                            </td>
                                            <td class="text-center"><p><span class="label {{ $invoice->label_status }}">{{ $invoice->status_description }}</span></p></td>
                                            <td class="text-right">{{ money_fmt($invoice->total) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            <th class="text-right">
                                                {{ money_fmt($invoices->sum('total')) }} {{ Session::get('coin') }}
                                            </th>                                
                                        </tr>
                                    </tfood>                                    
                                </table>
                            </div>
                        @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white" data-dismiss="modal">Cerar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Invoice Details MODDAL-->
        

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
        
        //Datepicker fecha del pago
        var date_input_1=$('#data_1 .input-group.date');
        date_input_1.datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+1d',
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

        $('#select_amount').on('ifChecked', function(event) {
            $('#other_amount').val('');
            $('#other_amount').attr('disabled', true);
        });

        $('#select_amount').on('ifUnchecked', function(event) {
            $('#other_amount').attr('disabled', false);
            $('#other_amount').focus();
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
            $("#hdd_discount_id").val(0);
            $("#hdd_net_debt").val({{ $contract->balance }});
            $("#type").val(null).trigger("change"); 
            $("input[id^='invoice']").iCheck('uncheck');
            $("input[id^='other_discount']").iCheck('uncheck');
            if ('{{ $contract->citizen->age_discount() }}'=='1'){
                $("input[id='age_discount']").iCheck('uncheck');
            } 
 
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
        //total = parseFloat(document.getElementById("amount["+i+"]").value);
        total = parseFloat({{ $contract->balance }});
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
            for (i=0; i< {{ $count_od }}; i++){
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
        $('#hdd_discount_id').val(discount_id);
        $('#hdd_net_debt').val(total);
        

        document.getElementById("total_monto").innerHTML = money_fmt(total)+" {{ Session::get('coin') }}";
        document.getElementById("total_desglose").innerHTML = "Total Saldo Actual - Descuento ("+money_fmt(tot_invoices)+" - "+money_fmt(discount)+")";        
    }       
    
    function money_fmt(num){
        num_fmt = num.toFixed(2).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        return num_fmt;
        //console.log (num_fmt);
    }

    });
    </script>

@endpush