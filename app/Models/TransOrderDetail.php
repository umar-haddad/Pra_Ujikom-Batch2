<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransOrderDetail extends Model
{
    protected $fillable = [
        'id_trans',
        'id_service',
        'qty',
        'subtotal',
        'notes'
    ];

    public function transOrder()
    {
        return $this->belongsTo(TransOrders::class, 'id_trans', 'id');
    }

    public function service()
    {
        return $this->belongsTo(TypeOfServices::class, 'id_service', 'id');
    }
}
