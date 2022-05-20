<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Slug extends Model
{
    /**
     * @param $title
     * @param $table
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public function createSlug($title, $table, $id = 0)
    {

        $slug = Str::slug($title);
        $slugs = DB::table($table)->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'");

        if ($slugs->count() === 0) {
            return $slug;
        } else {

            // Get the last matching slug
            $lastSlug = $slugs->orderBy('slug', 'desc')->first()->slug;

            // Strip the number off of the last slug, if any
            $lastSlugNumber = intval(str_replace($slug . '-', '', $lastSlug));

            // Increment/append the counter and return the slug we generated
            return $slug . '-' . ($lastSlugNumber + 1);
        }

        throw new \Exception('Can not create a unique slug');
    }
}
