<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';
    use HasFactory;


    public function product (){
        return $this->belongsTo(Product::class);
    }
}
