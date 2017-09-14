
@extends('layouts.blank_report')

@section('content')

        <!-- Header -->
        <table class="table" width="100%">
            <tbody>
                <tr>
                    <td class="text-left">
                        <img alt="image" style="max-height:110px; max-width:110px;" src="{{ $logo }}"/>
                    </td>
                    <td class="text-right">
                        <h2><strong>{{ $company->name }}</strong></h2>
                        <small>{{ $company->company_phone }}, {{ $company->company_email }}</small>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- /Header -->
        <br/>
        <!-- Body -->
        <table class="table" width="100%">
                <tbody>
                    <tr>
                        <td colspan="2" class="well text-center"><strong>DATOS DEL USUARIO</strong></td>
                    </tr>
                    <tr>
                        <td class="text-left">                                
                            <br/>
                            Para: <strong>{{ $invoice->contract->citizen->name }}</strong><br/>
                            {{ $invoice->contract->citizen->neighborhood }}. {{ $invoice->contract->citizen->street }}.<br/> 
                            # Int {{ $invoice->contract->citizen->number_int }}/ # Ext {{ $invoice->contract->citizen->number_ext }}<br/>
                            {{ $invoice->contract->citizen->municipality->name }}, {{ $invoice->contract->citizen->state->name }}<br/> 
                            <strong>Teléfono: </strong> {{ $invoice->contract->citizen->phone }}<br/>
                            <strong>Correo electrónico: </strong> {{ $invoice->contract->citizen->email }}<br/>
                            <br/>
                        </td>
                        <td class="text-right">
                            <h2>RECIBO No. {{ $invoice->id }}</h2>
                            <strong>Fecha:</strong> {{ $invoice->date->format('d/m/Y') }}</span><br/>
                            <strong>Contrato: {{ $invoice->contract->number }}</strong><br/> 
                            Tarifa: {{ $invoice->rate_description}}<br/>
                            <br/>
                        </td>
                    </tr>                    
                    <tr class="text-center">
                        <td class="well"><strong>DATOS DE FACTURACION</strong></td>
                        <td class="well"><strong>INFORMACION DE CONSUMO</strong></td>
                    </tr>                                            
                    <tr>
                        <td>
                            <br/>
                            <strong>Vencimiento:</strong> {{ $invoice->date_limit->format('d/m/Y') }}<br/>
                            <strong>Período de Consumo:</strong> {{ month_letter($invoice->month_consume, 'lg') }} {{ $invoice->year_consume }}<br/>
                            <br/>
                        </td>
                        <td class="text-right">
                            <br/>
                            <strong>Lectura Anterior:</strong> {{ ($invoice->reading_id)?$invoice->reading->previous_reading:'00000' }}<br/>
                            <strong>Lectura Actual:</strong> {{ ($invoice->reading_id)?$invoice->reading->current_reading:'00000' }}<br/>
                            <strong>Consumo:</strong> {{ ($invoice->reading_id)?$invoice->reading->consume:'00000' }}</span><br/>
                            <br/>                        
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td class="well"><strong>HISTORIAL DE CONSUMO</strong></td>
                        <td class="well"><strong>DETALLE DE FACTURACION</strong></td>
                    </tr>
                    <tr class="text-center">
                        <td></td>
                        <td>                        
                        <!-- Invoice Details -->
                            <table class="table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align:left">Descripción</th>
                                        <th align="right">Total {{ Session::get('coin') }}</th>
                                    </tr>
                                </thead>
                                <!-- foreach -->
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
                                <tfoot class="table" style="border:none">
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
                                        <td align="right"><strong>{{ money_fmt($invoice->total) }}</strong> {{ Session::get('coin') }}</td>
                                    </tr>                    
                                </tfoot>
                                <!-- /foreach -->
                            </table>
                        </div>    
                        <!-- Invoice Details -->
                        </td>
                    </tr>                                                                                       
                </tbody>
        </table>
        <!-- /Body -->
        <br/><br/>
        <!-- Message -->        
        <div class="well"><strong>Mensaje al ciudadano:</strong><br/>
            <small>{{ $invoice->message }}</small>
        </div>
        <!-- /Message -->
@endsection

