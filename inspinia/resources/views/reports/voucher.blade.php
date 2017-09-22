@extends('layouts.blank_report')

@push('stylesheets')
<style type="text/css">
    small {
    font-size: smaller;
    }
    table { border-collapse:collapse; }
    table thead th { border-bottom: 0.5px solid #e3e3e3; }
</style>    
@endpush

@section('content')
    <div class="row">        
        <table width="100%" class="table">
            <tbody>
                <tr>
                    <td class="text-left">
                        <strong>{{ $company->name }}</strong><br/>
                        {{ $company->address }}<br>
                        Teléfono: {{ $company->company_phone }}
                    </td>
                    <td align="right">
                        <h2>Comprobante de Pago No. <strong>{{ $payment->id }}</strong></h2>
                        <h3>Contrato No. <strong>{{ $payment->contract->number }}</strong></h3>
                        <strong>{{ $payment->contract->citizen->name }}</strong><br>
                        {{ $payment->contract->citizen->neighborhood }}, {{ $payment->contract->citizen->street }}<br>
                        {{ $payment->contract->citizen->municipality->name }}, {{ $payment->contract->citizen->state->name }}<br>
                        <p><span><strong>Fecha:</strong> {{ $payment->date->format('d/m/Y') }}</span></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <table class="table" width="100%">
        <thead>
            <tr>
                <th class="text-left">Item</th>
                <th align="right">Total {{ Session::get('coin') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payment->payment_details()->orderBy('type')->get() as $detail)
            <tr>
                <td class="text-left"><small>{{ $detail->description }}</small></td>
                <td class="text-right">
                    {{ money_fmt($detail->amount) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br/>
    <table class="table" width="100%">
        <tbody>
            <tr>
                <td>
                    <strong>TOTAL PAGO:</strong>
                </td>
                <td class="text-right">
                    <strong>{{ Session::get('coin') }} {{ money_fmt($payment->payment_details->sum('amount')) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
    
    <br/>
    <div>
        <strong>Saldo Restante:</strong> {{ money_fmt($payment->debt) }} {{ Session::get('coin') }}
    </div>
    <br/><br/>    
    <div class="well m-t"><strong>Observación</strong>
        {{ $payment->observation }}
    </div>
    
@endsection

@push('scripts')

@endpush