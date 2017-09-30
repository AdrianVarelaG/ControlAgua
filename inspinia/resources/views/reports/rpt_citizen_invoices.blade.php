
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
                        <h2><strong>Listado de Recibos por Ciudadano</strong></h2>                    
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- /Header -->
        <br/>
        
        <h3>{{ $citizen->name }}</h3>

        <!-- Body -->
        <table class="table" width="100%">
            <thead>
                <tr>
                    <th class="text-left">Contrato</th>
                    <th class="text-center">Recibo #</th>
                    <th class="text-center">Facturación</th>
                    <th class="text-right">Monto {{ Session::get('coin') }}</th>
                    <th class="text-center">Vencimiento</th>
                    <th class="text-left">Estatus</th>                    
                </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td class="text-left"><strong>{{ $invoice->contract->number }}</strong></td>
                    <td class="text-center"><strong>{{ $invoice->id }}</strong></td>
                    <td class="text-center">{{ $invoice->date->format('d/m/Y') }}</td>
                    <td class="text-right">{{ money_fmt($invoice->total) }}</td>
                    <td class="text-center">{{ $invoice->date_limit->format('d/m/Y') }}</td>
                    <td class="text-left">{{ $invoice->status_description }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <br/>
        <br/>    
@endsection

