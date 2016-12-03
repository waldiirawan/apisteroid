<?php

namespace Apisteroid\Request;

class Entity
{

    /*
     *  Set
     */
    public static function set($obj, $collection, $option = [])
    {
        $optionDefault = ['ignore' =>
            ['id', 'updated_at', 'created_at']
        ];
        $optionDefault = array_merge_recursive($optionDefault, $option);
        if( is_string($obj) && is_object(json_decode($obj, true))) {
            $obj = json_decode($obj);
            if(!is_object($obj)) {
                throw new \Exception("Opps, cannot get json object!");
                return false;
            }
        } else {
            $obj = (object) $obj;
        }
        if(!is_object($collection)) {
            throw new \Exception("Opps, cannot get collection object!");
            return false;
        }
        $collectionAttributes = $collection->getFillable();
        foreach ($collectionAttributes as $key => $value) {
            if (property_exists($obj, $value)) {
                if (in_array($key, $optionDefault['ignore'], TRUE)) {
                    // do nothing
                } else {
                    $collection->{$value} = $obj->{$value};
                }
            }
        }
        return $collection;
    }

}
