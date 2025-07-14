<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransOrders extends Model
{
    protected $fillable = [
        'id_customer',
        'order_end_date',
        'order_status',
        'order_code',
        'total',
        // 'order_pay',
        'order_change'];
    // ORM Laravel [object relation mapping]
    //examp : LEFT JOIN

    public function customer() {
        return $this->belongsTo(Customers::class, 'id_customer', 'id');
    }

    public function transOrderDetail() {
        return $this->hasMany(TransOrderDetail::class, 'id_trans');
    }

    public function getStatusTextAttribute() {
        switch ($this->order_status) {
            case '1' :
                return "sudah bayar";
                break;
            default:
                return "Baru";
                break;
        }
    }
}
