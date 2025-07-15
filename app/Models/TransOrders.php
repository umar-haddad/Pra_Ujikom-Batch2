<?php

namespace App\Models;

use App\Models\TransOrderDetail;
use Illuminate\Database\Eloquent\Model;

class TransOrders extends Model
{
    protected $fillable = ['id_customer', 'order_code', 'order_end_date', 'order_status',  'order_pay', 'order_change', 'total', 'note'];

    //relation : ORM (Object Relation Mapping)
    //LEFT JOIN
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'id_customer', 'id');
    }

    public function details()
    {
        return $this->hasMany(TransOrderDetail::class, 'id_trans');
    }

    public function getStatusTextAttribute()
    {
        switch ($this->order_status) {
            case '1':
                return "Selesai";
                break;

            default:
                return "Baru";
                break;
        }
    }
}