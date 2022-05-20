<?php

namespace App\Imports;


use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductUpdatesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        try {
            $i = 0;
            foreach ($rows as $row) {
                if ($i > 0) {
                    //[medicine name]


                    $exlProduct = Product::find($row[0]);
                    $desc = str_replace("[medicine name]", $exlProduct->meta_title, $row[2]);
                    $key = str_replace("[medicine name]", $exlProduct->meta_title, $row[3]);
                    $key2 = str_replace("[medicine online]", $exlProduct->meta_title, $key);
                    $exlProduct->meta_description = $desc;
                    $exlProduct->meta_keys = $key2;
                    $exlProduct->save();

                }
                $i++;
            }
        } catch (\Exception $e) {

            echo $e->getMessage();
            die;
        }
    }
}

