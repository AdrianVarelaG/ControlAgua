
@extends('layouts.blank_report')

@push('stylesheets')

@endpush

@section('content')

        <!-- Header -->
        <table class="table" width="100%">
            <tbody>
                <tr>
                    <td class="text-center">
                        <img alt="image" style="max-height:110px; max-width:110px;" src="{{ $logo }}"/>
                        <h3><strong>{{ $company->name }}</strong></h3>
                        <h2><strong>Listado de Contractos</strong></h2>                    
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- /Header -->
        <br/>
        
        <!-- Body -->
        <table class="table" width="100%">
            <thead>
            <tr>
                <th class="text-left">Nro Contrato</th>
                <th class="text-left">Ciudadano</th>
                <th class="text-left">RFC</th>
                <th class="text-right">Deuda {{ Session::get('coin') }}</th>
                <th class="text-center">Solvente hasta</th>
                <th class="text-center">Estatus</th>                    
            </tr>
            </thead>
            <tbody>
            @foreach($contracts as $contract)
            <tr>
                <td class="text-left"><small>{{ $contract->number }}</small></td>
                <td class="text-left"><small>{{ $contract->citizen->name }}</small></td>
                <td class="text-left"><small>{{ $contract->citizen->RFC }}</small></td>
                <td class="text-right"><small>{{ money_fmt($contract->balance) }}</small></td>
                <td class="text-right">
                    <small>{{ ($contract->last_invoice_canceled)?$contract->last_invoice_canceled->month.'/'.$contract->last_invoice_canceled->year:'Sin pagos' }}</small>
                </td>
                <td class="text-center"><small>{{ $contract->status_description }}</small></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <br/>
        <br/>    
@endsection

