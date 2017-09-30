
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
                        <h2><strong>Listado de Ciudadanos</strong></h2>                    
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
                        <th class="text-left">Nombre</th>
                        <th class="text-left">RFC</th>
                        <th class="text-left">Direccion</th>
                        <th class="text-right">Deuda {{ Session::get('coin') }}</th>
                        <th class="text-center">Estatus</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($citizens as $citizen)
                    <tr>
                        <td class="text-left"><small>{{ $citizen->name }}</small></td>
                        <td class="text-left"><small>{{ $citizen->RFC }}</small></td>
                        <td class="text-left"><small>{{ $citizen->address }}</small></td>                        
                        <td class="text-right"><small>{{ money_fmt($citizen->balance) }}</small></td>
                        <td class="text-center"><small>{{ $citizen->status_description }}</small></td>
                    </tr>
                    @endforeach
                    </tbody>
        </table>
        <br/>
        <br/>    
@endsection

