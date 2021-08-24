<?php

namespace App\Helpers;

use App\Models\Foo;
use App\Models\Bar;

use Carbon;
use View;

class TagManagerHelper {

    /**
     * Return list of all tags for objects, and get their values
     *
     * @param array $manualTags
     * @param array $objectTags
     *
     * @return array $datas
     */
    public static function getTagValues($manualTags, $objectTags) {

        $datas = [];

        // You can create tags for all templates
        $datas['today'] = Carbon::now()->format('d/m/Y');

        // Tags from objects
        if (is_array($objectTags)) {

            foreach ($objectTags as $class => $object) {

                switch ($class) {

                    case Foo::class :
                        
                        //from object param
                        $datas['foo_name'] = $object->numero;
                        $datas['foo_firstname'] = $object->firstname;
                        //or static values ? 
                        $datas['foo_static'] = 'This is a static value';

                    break;

                    case Bar::class :
                        
                        $datas['bar_code'] = $object->code;

                    break;

                    //add all your objects 
                    // ...
                }
            }
        }

        // Manual tags
        if (is_array($manualTags)) {

            foreach ($manualTags as $tag => $value) {
                //just put tag = value;
                $datas[$tag] = $value;
            }
        }

        return $datas;
    }
}
