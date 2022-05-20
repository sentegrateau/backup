<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Slug;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
		try{
		$i=0;
        foreach ($rows as $row) {
            $productImg = [];
			if($i>0){
            $slug = new Slug();
            $slugData = $slug->createSlug($row[3], 'products');
            $product = Product::create([
                'category_id' => $row[4],
                'name' => $row[3],
                'slug' => $slugData,
                'description' => $row[5],
                'short_description' => $row[6],
                'price' => $row[7],
                'special_price' => $row[8],
                'size' => $row[9],
                'quantity' => $row[10],
                'product_code' => $row[11],
                'weight' => $row[12],
                'featured' => $row[13],
                'status' => (!empty($row[14]))?$row[14]:0,
                //'defence' => $row[15]
            ]);
            $prodImg = new ProductImage();
            for ($i = 0; $i < 3; $i++) {
                if (!empty($row[$i])) {
                    $name = 'product_img_' . time() . $product->id . $row[$i];
                    Storage::disk('product_uploads')->put($name, file_get_contents(public_path() . '/product-img-exl-upload/' . $row[$i]));
                    $prodImg->createThumbs(file_get_contents(public_path() . '/product-img-exl-upload/' . $row[$i]), $name);
                    $productImg[] = ['product_id' => $product->id, 'image' => $name, 'created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')];
                }
            }
            ProductImage::insert($productImg);
			}
			$i++;
        }
		} catch (\Exception $e) {

			echo $e->getMessage();die;
		}
    }


}
