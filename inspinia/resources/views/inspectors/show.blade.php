@extends('layouts.app')

@push('stylesheets')

@endpush

@section('page-header')
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            
            <!-- ibox-title -->
            <div class="ibox-title">
                <h5> Inspector para captura de Lecturas</h5>
                <div class="ibox-tools">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-wrench"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <!-- /ibox-title -->
            
            <!-- ibox-content -->
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-4">
                    </div>
                    <div class="col-lg-4">
                        <div class="contact-box center-version">
                                <a style="cursor:default">
                                    <img alt="image" class="img-circle" src="{{ url('inspector_avatar/'.$inspector->id) }}">
                                    <h3 class="m-b-xs"><strong>{{ $inspector->name }}</strong></h3>
                                    <div class="font-bold"></div>
                                    <address class="m-t-md">
                                        <strong>{{ $inspector->ID_number }}</strong><br>
                                    </address>
                                </a>
                                <div class="contact-box-footer">
                                    <div class="m-t-xs btn-group">
                                        <small>
                                            <i class="fa fa-phone"></i>&nbsp;{{ $inspector->phone }}&nbsp;&nbsp;
                                            <i class="fa fa-mobile"></i>&nbsp;{{ $inspector->mobile }}<br/>
                                            <i class="fa fa-envelope-o"></i>&nbsp;{{ $inspector->email }}</a>
                                        </small>
                                    </div>
                                </div>
                        </div>
                        <!-- /contact-box -->  
                    </div>
                    <!-- /lg-4 -->
                    <div class="col-lg-4">
                    </div>                        
                

                </div>                                                        
                <!-- /row -->
                        <div class="form-group pull-right">
                            <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                <a href="{{URL::to('inspectors')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                            </div>
                        </div>                    
                        <br/>
                        <br/>            
            </div>
            <!-- /ibox-content -->                        
        

        </div>
    </div>
</div>
@endsection

@push('scripts')    
    
@endpush