<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id', 'name', 'barcode', 'quantity', 'unit',
        'price', 'price_total', 'vat_rate', 'vat_value', 'gross_value'
    ];
}
