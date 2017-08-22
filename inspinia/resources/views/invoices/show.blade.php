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
        
            <div class="row">
                <div class="text-right well m-t"><strong>{{ $company->name }}</strong><br>
                    <small>{{ $company->company_phone }}<br/> {{ $company->company_email }}</small>
                </div>
            </div>

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
                            <td class="text-center well m-t"><strong>INFORMACION DE CONSUMO</strong></td>
                        </tr>                        
                        <tr>
                            <td>
                                <span><strong>Vencimiento:</strong> {{ $invoice->date_limit->format('d/m/Y') }}</span><br>                                
                                <span><strong>Período de Consumo:</strong> {{ month_letter($invoice->month_consume, 'lg') }} {{ $invoice->year_consume }}</span>
                            </td>
                            <td class="text-right">
                                <span><strong>Lectura Anterior (m3):</strong> {{ ($invoice->reading_id)?$invoice->reading->previous_reading:'00000' }}</span><br>
                                <span><strong>Lectura Actual (m3):</strong> {{ ($invoice->reading_id)?$invoice->reading->current_reading:'00000' }}</span><br>
                                <span><strong>Consumo (m3):</strong> {{ ($invoice->reading_id)?$invoice->reading->consume:'00000' }}</span><br>
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
                        <td class="text-center well m-t"><strong>DETALLE DE FACTURACION</strong></td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            Aqui va el grafico de consumo
                        </td>
                        <td>
        
        <!-- /Invoice Details -->
            <table class="table table-responsive invoice-table">
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
                       
            <table class="table invoice-total" style="border:none">
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
                        <td align="right"><strong>TOTAL:</strong></td>
                        <td align="right">{{ money_fmt($invoice->total) }}</td>
                    </tr>                    
            </table>


                        </td>
                    </tr>
                </tbody>
            </table>
        </div>        

        <!-- Message -->        
        <div class="well m-t"><strong>Mensaje al ciudadano:</strong><br>
            <small>{{ $invoice->message }}</small>
        </div>
        <!-- /Message -->
        
        <div class="text-right">
            <a href="{{ route('invoices.invoice_pdf', Crypt::encrypt($invoice->id)) }}" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Imprimir</a>
            <a href="{{ route('invoices.index', [Crypt::encrypt($invoice->year), Crypt::encrypt($invoice->month)] ) }}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
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