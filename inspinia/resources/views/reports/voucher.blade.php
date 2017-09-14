@extends('layouts.blank_report')

@push('stylesheets')
<style type="text/css">
    small {
    font-size: smaller;
}
</style>
@endpush

@section('content')
<div class="wrapper wrapper-content p-xl">
    <div class="ibox-content p-xl">
                            <div class="row">
                                <div class="col-sm-6">
                                    <address>
                                        <strong>{{ $company->name }}</strong><br>
                                        {{ $company->address }}<br>
                                        <abbr>Teléfono:</abbr> {{ $company->company_phone }}
                                    </address>
                                </div>

                                <div class="col-sm-6 text-right">
                                    <h3>Comprobante de Pago No. <strong>{{ $payment->id }}</strong></h3>
                                    <h4>Contrato No. <strong>{{ $payment->contract->number }}</strong></h4>
                                    <address>
                                        <strong>{{ $payment->contract->citizen->name }}</strong><br>
                                        {{ $payment->contract->citizen->neighborhood }}, {{ $payment->contract->citizen->street }}<br>
                                        {{ $payment->contract->citizen->municipality->name }}, {{ $payment->contract->citizen->state->name }}<br>
                                        <abbr>Teléfono:</abbr> {{ $payment->contract->citizen->phone }}
                                    </address>
                                    <p>
                                        <span><strong>Fecha:</strong> {{ $payment->date->format('d/m/Y') }}</span><br/>
                                    </p>
                                </div>
                            </div>

                            <div class="table-responsive m-t">
                                <table class="table invoice-table">
                                    <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th align="right">Total {{ Session::get('coin') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payment->payment_details()->orderBy('type')->get() as $detail)
                                        <tr>
                                            <td><small>{{ $detail->description }}</small></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td align="right">
                                            {{ money_fmt($detail->amount) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div><!-- /table-responsive -->

                            <table class="table invoice-total">
                                <tbody>
                                    <tr>
                                        <td><strong>TOTAL PAGO:</strong></td>
                                        <td>{{ Session::get('coin') }} {{ money_fmt($payment->payment_details->sum('amount')) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="well m-t"><strong>Observación</strong>
                                {{ $payment->observation }}
                            </div>
    
    </div>
</div>
@endsection

@push('scripts')

@endpush