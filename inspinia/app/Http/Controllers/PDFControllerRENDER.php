<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Company;
use App\Http\Requests\Invoice\InvoiceRequestPrint;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Crypt;
use File;
use PDF;
use Session;
use View;
use App;


class PDFController extends Controller
{
 
    /*
     * Download file from DB  
    */ 
    public function invoice_pdf($id)
    {
        $company = Company::first();
        $invoice = Invoice::find(Crypt::decrypt($id));
        $data=[
            'company' => $company,
            'invoice' => $invoice,
            'logo' => 'data:image/png;base64, '.$company->logo 
        ];
        $pdf = PDF::loadView('reports/invoice', $data);
        return $pdf->download('Recibo Nro '.Crypt::decrypt($id).'.pdf');

    }

    /*
     * Download file from DB  
    */ 
    public function invoices_pdf(InvoiceRequestPrint $request)
    {
        $final_view='';
        $company = Company::first();
        $invoices = Invoice::where('id', '>=' , $request->input('invoice_from'))
                            ->where('id', '<=' , $request->input('invoice_to'))->get();
        
        foreach ($invoices as $invoice) {
            
            $data=[
                'company' => $company,
                'invoice' => $invoice,
                'logo' => 'data:image/png;base64, '.$company->logo
            ];

            $invoice_view = View::make('reports/invoice', $data )->render();
            
            $final_view = $final_view."<div class='saltopagina'></div>".$invoice_view;
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($final_view);
        ini_set('max_execution_time', 300);
        return $pdf->download('Recibos del Nro '.$request->input('invoice_from').' al Nro '.$request->input('invoice_to').'.pdf');

    }

    /*
     * Download file from DB  
    */ 
    public function print_voucher($id)
    {
        $company = Company::first();
        $payment = Payment::find(Crypt::decrypt($id));
        $data=[
            'company' => $company,
            'payment' => $payment,
        ];
        $pdf = PDF::loadView('reports/voucher', $data);
        return $pdf->download('Voucher.pdf');

    }

}
