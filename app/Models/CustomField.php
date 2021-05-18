<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 5/18/2021
 * Time: 2:31 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name'
    ];
}