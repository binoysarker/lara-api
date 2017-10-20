<?php
/**
 * Created by PhpStorm.
 * User: binoy
 * Date: 10/18/17
 * Time: 3:57 PM
 */

namespace App\Transformers;


use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return[
          'username'    =>  $user->username,
          'avatar'    =>  $user->avatar(),
        ];
    }
}