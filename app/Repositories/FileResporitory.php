<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/6 0006
 * Time: 下午 3:13
 */

namespace App\Repositories;


class FileResporitory extends Repository
{
    function model()
    {
        return 'App\Models\File';
    }

//    public function getFile($file)
//    {
//        $file = $file->with(
//            ['user' => function ($q) {
//                $q->select('user_id');
//            }
//            ]
//        )
//            ->with('component.layers')
//            ->with('token')->get();
//
//
//        return $file;
//    }

}