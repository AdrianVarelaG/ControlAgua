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
        //setPaper(0,0, alto, ancho) en ptos 1 mm = 2.83465 ptos
        $pdf->setPaper([0, 0, 420, 595], 'landscape');         
        return $pdf->download('Recibo Nro '.Crypt::decrypt($id).'.pdf');

    }

    /*
     * Download file from DB  
    */ 
    public function invoices_pdf(InvoiceRequestPrint $request)
    {
        $company = Company::first();
        $invoices = Invoice::where('id', '>=' , $request->input('invoice_from'))
                            ->where('id', '<=' , $request->input('invoice_to'))->get();
        $data=[
            'company' => $company,
            'invoices' => $invoices,
            'logo' => 'data:image/png;base64, '.$company->logo
        ];
        $pdf = PDF::loadView('reports/invoice_all', $data);
        //setPaper(0,0, alto, ancho) en ptos 1 mm = 2.83465 ptos
        $pdf->setPaper([0, 0, 420, 595], 'landscape'); 
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
        //setPaper(0,0, alto, ancho) en ptos 1 mm = 2.83465 ptos
        $pdf->setPaper([0, 0, 420, 595], 'landscape'); 
        return $pdf->download('Comprobante #'.$payment->id.'.pdf');

    }


}
