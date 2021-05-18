<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\Product;
use App\Models\ProductField;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 5/17/2021
 * Time: 5:06 PM
 */

class FileController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function parseFile(Request $request)
    {
        $path = $request->file('file')->getRealPath();
        $toReturn = $this->getCsvData($path);
        $csvData = array_slice($toReturn, 0, 2);
        $mappedHeaders = [];
        $columns = Product::ASSOC_COLUMNS;
        $customFields = CustomField::all()->unique()->keyBy('name')->toArray();
        foreach ($csvData[0] as $item) {
            $column = self::recursiveArraySearch($item, array_merge($customFields, $columns));
            $mappedHeaders[] = $column ? : $columns['name'][0];
        }

        return response()->json([
            'success' => true,
            'headers' => $csvData,
            'columns' => $mappedHeaders
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAttr(Request $request)
    {
        $attrName = str_replace(' ', '_', strtolower($request->get('name')));
        $field = CustomField::firstOrCreate([
            'name' => $attrName
        ]);

        return response()->json($field);
    }

    public function import(Request $request)
    {
        $path = $request->file('file')->getRealPath();
        $fields = explode(',', $request->get('fields'));

        $customFields = array_diff($fields, Product::getFields());
        $customFields = CustomField::whereIn('name', $customFields)->get();
        $csvData = $this->getCsvData($path);
        $fields = array_slice($fields, 0, count($csvData[0]));
        if (count($fields) !== count(array_unique($fields))) {
            return response()->json(['success' => false, 'error' => 'Headers shouldn`t have duplicate'], 422);
        }
        foreach ($csvData as $key => $item) {
            if ($key === 0) continue; //skip csv headers
            $row = array_combine($fields, $item);
            $product = Product::create($row);
            foreach ($customFields as $field) {
                ProductField::create([
                    'field_id' => $field->id,
                    'product_id' => $product->id,
                    'value'    => $row[$field->name]
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * @param $needle
     * @param $haystack
     * @return bool|int|string
     */
    public function recursiveArraySearch($needle, $haystack) {
        foreach($haystack as $key => $value) {
            $current_key=$key;
            if($needle === $value OR (is_array($value) && self::recursiveArraySearch($needle, $value) !== false)) {
                return $current_key;
            }
        }

        return false;
    }

    /**
     * @param $path
     * @return array
     */
    protected function getCsvData($path)
    {
        $toReturn = [];
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 3000, ",")) !== FALSE) {
                $row = [];
                foreach ($data as $column) {
                    $encoding = mb_detect_encoding($column, mb_detect_order(), false);
                    $row[] = $encoding !== 'ASCII' ? iconv ('WINDOWS-1251', "UTF-8//IGNORE", $column) : $column;

                }
                $toReturn[] = $row;
            }
            fclose($handle);
        }

        return $toReturn;
    }
}