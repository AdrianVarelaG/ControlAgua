
@extends('layouts.blank_report')

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
            
            <!-- Body -->
            <table class="table table-striped table-hover" >
                    <thead>
                    <tr>
                        <th class="text-center">Recibo #</th>
                        <th>Contrato</th>
                        <th>Facturación</th>
                        <th>Monto {{ Session::get('coin') }}</th>
                        <th>Vencimiento</th>
                        <th>Estatus</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td class="text-center">{{ $invoice->id }}</td>
                        <td>
                          <strong>{{ $invoice->contract->number }}</strong><br/>
                          <small>{{ $invoice->contract->citizen->name }}</small>
                        </td>
                        <td>{{ $invoice->date->format('d/m/Y') }}</td>
                        </td>
                        <td>{{ money_fmt($invoice->total) }}</td>
                        <td>{{ $invoice->date_limit->format('d/m/Y') }}</td>
                        @php
                        @endphp
                        <td><p><span class="label {{ $invoice->label_status }}">{{ $invoice->status_description }}</span></p></td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>
            </table>
            <!-- /Body -->

@endsection

