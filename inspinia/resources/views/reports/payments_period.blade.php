
@extends('layouts.blank_report')

@push('stylesheets')
<style type="text/css">

table.center {
    margin-left: auto;
    margin-right: auto;
}

html, body {
    width: 100%;
}

</style>
@endpush

@section('content')

        <!-- Header -->
        <table class="table" width="100%">
            <tbody>
                <tr>
                    <td class="text-center">
                        <img alt="image" style="max-height:110px; max-width:110px;" src="{{ $logo }}"/>
                        <h2><strong>{{ $company->name }}</strong></h2>
                        <small>{{ $company->company_phone }}, {{ $company->company_email }}</small>                    
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- /Header -->
        <br/>
        
        <h4>Pagos Desde {{ $from }} hasta {{ $to }}</h4>
        <h4>Total Pagos: {{ ($payments->count())?$payments->count():0 }}</h4>
        <!-- Body -->
        <table class="table" width="100%">
            <thead>
                    <tr>
                        <th class="text-left">Nombre</th>
                        <th class="text-left">Tarifa</th>
                        <th class="text-center">Contrato</th>
                        <th class="text-left">Domicilio</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-right">Monto {{ Session::get('coin') }}</th>
                        <th class="text-center">Folio</th>
                        <th class="text-left">Descripción</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td><small><strong>{{ $payment->contract->citizen->name }}</strong></small></td>
                        <td><small>{{ $payment->contract->rate->name }}</small></td>                        
                        <td class="text-center"><small><strong>{{ $payment->contract->number }}</strong></small></td>
                        <td><small>{{ $payment->contract->address }}</small></td>
                        <td class="text-center"><small>{{ $payment->date->format('d/m/Y') }}</small></td>
                        <td class="text-right"><small>{{ money_fmt($payment->amount) }}</small></td>
                        <td class="text-center"><small>{{ $payment->folio }}</small></td>
                        <td><small>{{ $payment->description }}</small></td>
                    </tr>
                    @endforeach
                    </tbody>
        </table>
        <br/>
        <br/>
    
    <!-- Resumen Footer -->
    @if($payments_by_municipality)
        <table width="200" border="1" class="table center">
            <thead>
                <tr>
                    <th colspan="2" class="text-center">Total Recaudado</th>
                </tr>
            </thead>            
            <tbody>
                @php($total_municipalities=0)
                @foreach($payments_by_municipality as $payment)
                    <tr>
                        <td width="60%">{{ $payment->municipality }}</td>
                        <td width="40%" class="text-right">{{ money_fmt($payment->amount) }} {{ Session::get('coin') }}</td>                    
                    </tr>
                    @php($total_municipalities = $total_municipalities + $payment->amount)
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td width="60%"><strong>TOTAL</strong></td>
                    <td width="40%" class="text-right"><strong>{{ money_fmt($total_municipalities) }} {{ Session::get('coin') }}</strong></td>
                </tr>                
            </tfoot>
        </table>
    @endif
    <!-- /Resumen Footer -->

@endsection

