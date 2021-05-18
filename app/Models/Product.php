<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 5/18/2021
 * Time: 11:07 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'brand',
        'price',
        'url',
        'description'
    ];

    const ASSOC_COLUMNS = [
        'name' => ['name'],
        'brand' => ['brand', 'publisher'],
        'price' => ['price'],
        'type' => ['type', 'variant'],
        'url' => ['url', 'product_url'],
        'description' => ['description']
    ];

    /**
     * @return array
     */
    public static function getFields()
    {
        $enity = new self();

        return $enity->fillable;
    }
}