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
        $obj = json_decode($obj);
        if(!is_object($obj)) {
            throw new \Exception("Opps, cannot get json object!");
            return false;
        }
        if(!is_object($collection)) {
            throw new \Exception("Opps, cannot get collection object!");
            return false;
        }
        $collectionAttributes = $collection->getAttributes();
        foreach ($collectionAttributes as $key => $value) {
            if (property_exists($obj, $key)) {
                if (in_array($key, $optionDefault['ignore'])) {
                    // do nothing
                } else {
                    $collection->{$key} = $obj->{$key};
                }
            }
        }
        return $collection;
    }

}
