<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 5/18/2021
 * Time: 2:27 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductField extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'field_id',
        'product_id',
        'value'
    ];
}