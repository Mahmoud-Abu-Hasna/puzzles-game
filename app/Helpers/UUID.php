<?php
/**
 * Created by PhpStorm.
 * User: Mahmoud
 * Date: 2/12/2018
 * Time: 8:50 PM
 */

namespace App\Helpers;

use Illuminate\Support\Facades\Password;

class UUID
{

    public static function generate($length=64, $modelClass = null, $fieldName=null)
    {
        $token = substr(Password::getRepository()->createNewToken(), 0, $length);

        if ($modelClass && $fieldName) {
            if ($modelClass::where($fieldName, '=', $token)->exists()) {
                //Model Found -- call self.
                self::generate($length, $modelClass, $fieldName);
            } else {
                //Model Not found. is uinque
                return $token;
            }
        } else {
            return $token;
        }
    }

}