@extends('layouts.app')

@push('stylesheets')

@endpush

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="wrapper wrapper-content animated fadeInRight">
                
        <!-- ibox-content -->
        <div class="ibox-content p-xl">
        
        <!-- Header -->
        <table class="table" width="100%">
            <tbody>
                <tr>
                    <td class="text-left">
                        <img alt="image" class="img-thumbnail" style="max-height:110px; max-width:110px;" src="{{ url('company_logo/'.$company->id) }}"/>
                    </td>
                    <td class="text-right">
                        <h3><strong>{{ $company->name }}</strong></h3>
                        <small>{{ $company->company_phone }}, {{ $company->company_email }}</small>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- /Header -->

            <!-- Header -->
            <div class="row">
                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <td colspan="2" class="text-center well m-t"><strong>DATOS DEL USUARIO</strong></td>
                        </tr>
                        <tr>
                            <td class="text-left">                                
                                <span>Para:</span>
                                    <strong>{{ $invoice->contract->citizen->name }}</strong>
                                    <p> {{ $invoice->contract->citizen->neighborhood }}. {{ $invoice->contract->citizen->street }}.<br/> # Int {{ $invoice->contract->citizen->number_int }}/ # Ext {{ $invoice->contract->citizen->number_ext }}</p>
                                    <strong>{{ $invoice->contract->citizen->municipality->name }}, {{ $invoice->contract->citizen->state->name }}</strong><br/> 
                                    <span><strong>Teléfono: </strong> {{ $invoice->contract->citizen->phone }}</span>                            
                            </td>
                            <td class="text-right">
                                <h3><strong>RECIBO No. {{ $invoice->id }}</strong></h3>
                                <p>
                                    <span><strong>Fecha:</strong> {{ $invoice->date->format('d/m/Y') }}</span><br/>
                                </p>                                
                                <address>
                                    <strong>Contrato: {{ $invoice->contract->number }}</strong> 
                                    <br>Tarifa: {{ $invoice->rate_description}}<br>
                                </address>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center well m-t"><strong>DATOS DE FACTURACION</strong></td>
                            <td class="text-center well m-t"><strong>DETALLE DE FACTURACION MENSUAL</strong></td>
                        </tr>                        
                        <tr>
                            <td>
                                <span><strong>Vencimiento:</strong> {{ $invoice->date_limit->format('d/m/Y') }}</span><br>                                
                                <span><strong>Período de Consumo:</strong> {{ month_letter($invoice->month_consume, 'lg') }} {{ $invoice->year_consume }}</span>
                            </td>
                            <td class="text-right">
                                <!-- /Invoice Details -->
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th style="text-align:left">Descripción</th>
                                            <th align="right">Total {{ Session::get('coin') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $tot=0;
                                            $details = $invoice->invoice_details()->where('movement_type', '!=', 'CI')->get();
                                            $iva = $invoice->invoice_details()->where('movement_type', 'CI')->first();
                                        @endphp
                                        @foreach($details as $detail)
                                            <tr>
                                                <td style="text-align:left"><small>{{ $detail->description }}</small></td>
                                                <td align="right">{{ money_fmt($detail->sub_total) }}</td>
                                                @php($tot=$tot+$detail->sub_total)
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- /Invoice Details -->
                    
                                <table class="table table-condensed invoice-total" style="border:none">
                                    @if($iva)                    
                                    <tr>
                                        <td align="right"><strong>Sub Total:</strong></td>
                                        <td align="right">{{ money_fmt($tot) }}</td>
                                    </tr>
                                    <tr>
                                        <td align="right"><strong>IVA ({{ $iva->percent }}%):</strong></td>
                                        <td align="right">{{ money_fmt($iva->sub_total) }}</td>
                                    </tr>                    
                                    @endif
                                    <tr>
                                        <td align="right"><strong>TOTAL MES:</strong></td>
                                        <td align="right">{{ money_fmt($invoice->total) }}</td>
                                    </tr>                    
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /Header -->
        
        <div class="row">
            <table class="table table-condensed">
                <tbody>
                    <tr>
                        <td class="text-center well m-t"><strong>HISTORIAL DE CONSUMO</strong></td>
                        <td class="text-center well m-t"><strong>INFORMACION DE CONSUMO</strong></td>
                    </tr>
                    <tr>
                        <td class="text-center">
                        </td>
                        <td class="text-right">
                            <span><strong>Saldo Anterior {{ Session::get('coin') }}:</strong> {{ money_fmt($invoice->previous_debt) }}</span><br/>
                            <span><strong>Cargo Mensual {{ Session::get('coin') }}:</strong> {{ money_fmt($invoice->total_calculated()) }}</span><br/><br/>
                            <strong style="font-size:14px">TOTAL A PAGAR {{ Session::get('coin') }}:</strong> {{ money_fmt($invoice->previous_debt + $invoice->total_calculated()) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>        

        <!-- Message -->        
        <div class="well m-t"><strong>Mensaje al ciudadano:</strong><small> {{ $invoice->message }}</small>
        </div>
        <!-- /Message -->
        
        <div class="text-right">
            <a href="{{ route('invoices.invoice_pdf', Crypt::encrypt($invoice->id)) }}" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Imprimir</a>
            <a href="{{ URL::previous() }}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
        </div>                            
    
    </div>
    <!-- ibox-content -->

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

@endpush