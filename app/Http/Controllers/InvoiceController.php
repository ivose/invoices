<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
//use App\Libs\PDF2Text;
//use Spatie\PdfToText\Pdf;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class InvoiceController extends Controller
{
    public function index()
    {

        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('infologin', 'You have to log in!');
        }
        //dd(auth()->check());
        $invoices = Invoice::all();
        $total_items = 0;
        $total_price = 0;
        $inv_count = $invoices->count();
        $min_items = 999999;
        $max_items = 0;
        $the_seapest_inv = 999999999;
        $the_most_expensive_inv = 0;
        foreach ($invoices as &$inv) {
            $inv->itemscount = $inv->items->count();
            $inv->totalprice = $inv->totalPrice();
            $total_items += $inv->itemscount;
            $total_price += $inv->totalprice;
            if ($min_items > $inv->itemscount) $min_items = $inv->itemscount;
            if ($max_items < $inv->itemscount) $max_items = $inv->itemscount;
            if ($the_seapest_inv > $inv->totalprice) $the_seapest_inv = $inv->totalprice;
            if ($the_most_expensive_inv < $inv->totalprice) $the_most_expensive_inv = $inv->totalprice;
        }
        $avg_items = $inv_count ? $total_price / $total_items : 0;
        $avg_price = $total_items ? $total_price / $total_items : 0;
        $avg_sum = $inv_count ? $total_price / $inv_count : 0;
        return view('invoices.list', compact(
            'invoices',
            'total_items',
            'total_price',
            'inv_count',
            'avg_items',
            'avg_price',
            'avg_sum',
            'min_items',
            'max_items',
            'the_seapest_inv',
            'the_most_expensive_inv'
        ));
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function getUpload()
    {
        //dd(auth()->check());
        //P2
        return view('invoices.upload');
    }


    public function postUpload(Request $request)
    {
        $request->validate([
            //P3
            'pdf' => 'required|mimetypes:application/pdf|max:2048',
        ]);

        list($dir, $file) = [storage_path('pdfs'), "invoice.pdf"];
        $path = "$dir/$file";
        if (file_exists($path)) unlink($path);
        $request->pdf->move($dir, $file);


        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile($path);
        $content = $pdf->getText();
        $content = spl($content, "\n");
        $invnr = "";

        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile($path);
        $content = $pdf->getText();
        $content = preg_split('/valueGross/', $content, 2);
        $invnr = $content[0];
        $invnr = substr($invnr, strpos($invnr, $s = "Date and place of invoice issueNo.\n") + strlen($s));
        $invnr = substr($invnr, 0, strpos($invnr, 'ORIGINAL')); //P4.a
        if (!preg_match("/^\d+\/\d+\/\d+\/([a-zA-Z0-9\-\_])+$/", $invnr)) { //P5
            return redirect()->route('upload')
                ->with('othererrors', 'Cannot detect the invoice number from the pdf-file!');
        }
        $content = trim($content[1]);
        $content = substr($content, 5); //"value" away

        ////P4.b
        $rp = "[0-9]+\,[0-9]{2}"; //regular expression for price
        $re = "[0-9]{10} [0-9]{3}[0-9]+[a-zA-Z]+$rp$rp [0-9][0-9]?\%$rp$rp"; //regular expression for getting important data
        $content = join(" ", spl($content, "\n"));
        preg_match_all("/$re/", $content, $products, PREG_OFFSET_CAPTURE); //picking up all matches

        $products = $products[0];
        if (!$products) { //P5
            return redirect()->route('upload')
                ->with('othererrors', 'Cannot detect any product from the pdf-file!');
        }
        Invoice::whereReference($invnr)->delete(); //p10
        //Invoice::whereReference($invnr)->whereUserId(auth()->id())->delete();
        $i = 0;
        foreach ($products as $k => &$m) { //P4.c
            $m['len'] = strlen($m[0]);
            $name = substr($content, $i);
            $p = strpos($name, $m[0]);
            $m['name'] = substr($content, $i, $p);
            $i = $m['len'] + $m[1];
            $k1 = $k + 1;
            if (startsWith($m['name'], "$k1")) $m['name'] = substr($m['name'], strlen("$k1")); //nmbrer ees maha
            //barcode:
            $m['barcode'] = substr($m[0], 0, 14);
            $m[0] = substr($m[0], 14);
            //Quantity
            $p = strpos($m[0], preg_split("/\d+/", $m[0], 2)[1]);
            $m['quantity'] = $this->str2nr(substr($m[0], 0, $p));
            $m[0] = substr($m[0], $p);
            //Unit
            $p = strpos($m[0], preg_split("/[a-zA-Z]+/", $m[0], 2)[1]);
            $m['unit'] = substr($m[0], 0, $p);
            $m[0] = substr($m[0], $p);
            //Unit price net
            $p = strpos($m[0], preg_split("/$rp/", $m[0], 2)[1]);
            $m['price'] = $this->str2nr(substr($m[0], 0, $p));
            $m[0] = substr($m[0], $p);
            //Net value
            $p = strpos($m[0], preg_split("/$rp/", $m[0], 2)[1]);
            $m['price_total'] = $this->str2nr(substr($m[0], 0, $p));
            $m[0] = substr($m[0], $p);
            //Vat rate
            $p = strpos($m[0], preg_split("/ [0-9][0-9]?\%/", $m[0], 2)[1]);
            $m['vat_rate'] = $this->str2nr(trim(substr($m[0], 0, $p)));
            $m[0] = substr($m[0], $p);
            //VAT value
            $p = strpos($m[0], preg_split("/$rp/", $m[0], 2)[1]);
            $m['vat_value'] = $this->str2nr(substr($m[0], 0, $p));
            $m[0] = substr($m[0], $p);

            $m['gross_value'] = $this->str2nr($m[0]);

            //If continues in the next page, then headers/footers away
            if ($p = strpos($m['name'], '      ORIGINALStrona')) {
                $m['name'] = preg_split("/      ORIGINALStrona \d+/", $m['name'])[1];
            }
            unset($m[0], $m[1], $m['len']);
        }

        // Create a new Invoice instance
        $invoice = new Invoice([ //P6
            'reference' => $invnr,
            'user_id' => auth()->id(),
        ]);
        // Save the invoice to the database
        $invoice->save();

        foreach ($products as $prod) { //P6
            $prod['invoice_id'] = $invoice->id;
            $product = new InvoiceItem($prod);
            $product->save();
        }

        if (file_exists($path)) unlink($path);

        return redirect()->route('home')
            ->with('success', 'The PDF file has been submitted successfully!'); //p9
    }

    private function str2nr($nr)
    {
        $nr = str_replace(',', '.', $nr);
        $nr = str_replace(' ', '', $nr);
        $nr = floatval($nr);
        return $nr;
    }
}
