<?php
/**
 * Created by PhpStorm.
 * User: see
 * Date: 7/27/2019
 * Time: 8:12 PM
 */

namespace App\Http\Helpers;


class permissionsHelper
{

    /**
     * Check if the permission checkbox must be checked or not
     *
     * @param $permissions_list
     * @param $current
     * @return string
     */


    public static function checkValidation($permissions_list, $premission, $current)
    {
        return isset( $permissions_list[$premission] )? ($permissions_list[$premission][$current]?'checked':''):'';
    }

}