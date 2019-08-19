<?php


namespace App\Http\Controllers\Api\Serializer;

use League\Fractal\Serializer\ArraySerializer;

class CustomSerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        return ['data' => $data,'status_code'=>200,'message'=>'success'];
    }

    public function item($resourceKey, array $data)
    {
        return ['data' => $data,'status_code'=>200,'message'=>'success'];
    }
}