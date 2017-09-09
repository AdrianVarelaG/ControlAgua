@extends('layouts.app')

@push('stylesheets')

@endpush

@section('page-header')
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            {{ Form::open(array('url' => 'payments/' . $payment->id, 'id'=>'form'), ['class'=>'form-horizontal'])}}
            @if($payment->id)
                {{ Form::hidden ('_method', 'PUT') }}
            @endif
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="ibox-content p-xl">
                            <div class="row">
                                <div class="col-sm-6">
                                    <address>
                                        <strong>{{ $company->name }}</strong><br>
                                        {{ $company->address }}<br>
                                        <abbr>Teléfono:</abbr> {{ $company->company_phone }}
                                    </address>
                                    <div class="form-group">
                                    <label>Nro FOLIO *</label>
                                        <div class="input-group m-b col-sm-6">
                                            {!! Form::text('folio', $payment->folio, ['id'=>'folio', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'', 'maxlength'=>'100', 'required']) !!}
                                        </div>
                                    </div>                                                                
                                </div>

                                <div class="col-sm-6 text-right">
                                    <h3>Comprobante de Pago No. <strong>{{ $payment->id }}</strong></h3>
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
                                        <th>Total {{ Session::get('coin') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($payment->payment_details()->orderBy('type')->get() as $detail)
                                        <tr>
                                            <td><small>{{ $detail->description }}</small></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                            {{ money_fmt($detail->amount) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div><!-- /table-responsive -->

                            <table class="table invoice-total">
                                <tbody>
                                    <td><strong>TOTAL PAGO:</strong></td>
                                    <td>{{ Session::get('coin') }} {{ money_fmt($payment->payment_details->sum('amount')) }} </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="well m-t"><strong>Observación</strong>
                                {{ $payment->observation }}
                            </div>
                            <div class="text-right">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Guardar</button>                                    
                                    <a href="{{ route('payments.print_voucher', Crypt::encrypt($payment->id)) }}" class="btn btn-sm btn-primary"><i class="fa fa-print"></i> Imprimir</a>
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                            </div>                            
                        </div>
                </div>
            </div>
        {{ Form::close() }}
        </div>
    </div>
@endsection

@push('scripts')    

<!-- Page-Level Scripts -->
<script>
          
    $(document).ready(function() {
                
        $("#folio").focus();

        // Validation
        $("#form").validate({
            submitHandler: function(form) {
                $("#btn_submit").attr("disabled",true);
                form.submit();
            }        
        });
        
    });
    </script>

@endpush