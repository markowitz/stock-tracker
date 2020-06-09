<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Stock extends Model
{
    protected $table = 'stock';

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track()
    {
        if($this->retailer->name === 'Best Buy') {
            $result = Http::get('http://foo.test')->json();

            $this->update([
                'in_stock' => $result['available'],
                'price' => $result['price']
            ]);
        }
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }
}
