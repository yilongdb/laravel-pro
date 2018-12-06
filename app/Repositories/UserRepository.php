<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5 0005
 * Time: 下午 5:18
 */

namespace App\Repositories;


class UserRepository extends Repository
{
    public function model()
    {
        return 'App\Models\User';
    }



    public function confirm($id, $confirmation_code){
        return $this->model->find($id)->where('confirmation_code', '=', $confirmation_code)->firstOrFail();
    }

}