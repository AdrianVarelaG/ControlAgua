@extends('layouts.blank_report')

@push('stylesheets')
@endpush


@section('main_container')

<style type="text/css">
  .table-borderless tbody tr td,
  .table-borderless tbody tr th,
  .table-borderless thead tr th,
  .table-borderless thead tr td,
  .table-borderless tfoot tr th,
  .table-borderless tfoot tr td {
    border: none;
}

  .small {
    font-size: small;
</style>          
          

        <!-- x_content -->
        <div class="x_content">



          <h1>Recibo {{ $payment->id }}</h1>
          <table class="table">
            <thead>
              <tr>
                <th>De</th>
                <th>Para</th>
                <th>Data</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <strong>{{ $owner->name }}</strong>
                  <br>Propiedad: {{ $condominium->name }}, {{ $property->code  }}
                  <br>Teléfono: {{ $owner->phone }}
                  <br>Correo electrónico: {{ $owner->email }}                  
                </td>
                <td>
                  <strong>Condominio {{ $condominium->name }}</strong>
                  <br>{{ $condominium->address  }}, {{ $condominium->state->name  }}, {{ $condominium->country->iso }}
                  <br>
                  <br>{{ $condominium->phone1  }}
                  <br>{{ $condominium->email  }}                  
                </td>
                <td>
                  <b>Nº Referencia {{ $payment->reference }}</b>
                  <br>
                  <br>
                  <b>Tipo de Pago:</b> 
                  @if ($payment->payment_method == 'EF')
                      Efectivo
                  @elseif ($payment->payment_method == 'CH')
                      Cheque
                  @elseif ($payment->payment_method == 'TA')
                      Transferencia
                  @endif
                  <br>
                  <b>Fecha de pago:</b> {{ $payment->date->format('d/m/Y') }}
                  <br>
                  <b>Cuenta:</b> {{ $payment->account->name }}
                  <br>
                  <b>Banco:</b> {{ ($payment->account->type == 'B')?$payment->account->bank->name:''}}
                  <br>
                  <b># </b> {{ ($payment->account->type == 'B')?$payment->account->account_number:'' }}
                </td>
              </tr>
            </tbody>
          </table>

        @if($payment->concept)  
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td><p><b>Concepto: </b><small>{{ $payment->concept }}</small></p></td>
              </tr>
            </tbody>
          </table>
        @endif
                    
          <!-- Listado de cuotas por propiedad -->  
          @if($quotas->count())
            <table class="table">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Fecha límite</th>
                  <th>Tipo de cuota</th>
                  <th>Concepto</th>
                  <th class="text-right">Monto</th>
                </tr>
              </thead>
              <tbody>                  
              <?php $i=1?>
              @foreach($quotas as $quota)
                <tr>
                  <td class="text-center">
                  {{ $i++ }}
                  </td>
                  <td class="text-center">{{ $quota->limit_payment->format('d/m/Y') }}</td>
                  <td>{{ $quota->income_type->name }}</td>
                  <td><small>{{ $quota->concept }}</small></td>
                  <td class="text-right">{{ money_fmt($quota->mount) }}</td>
                </tr>
              @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>TOTAL</th>
                  <th class="text-right">{{ money_fmt($payment->mount) }}</th>
                </tr>
              </tfoot>
            </table>                  
          @endif
          
          @if( $payment->comment)
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <td><p><b>Comentario de administrador: </b>{{ $payment->comment }}</p></td>
                </tr>
              </tbody>
            </table>
          @endif
      
        </div>
        <!-- /x_content -->

 <!--
 <footer>
  <font size="1">www.con<strong>Dominio</strong>.com</font>
</footer>
-->                       
@push('scripts')
@endpush
