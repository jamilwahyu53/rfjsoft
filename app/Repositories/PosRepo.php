<?php

namespace App\Repositories;

use App\Models\group;
use App\Models\MasterPos;
use Illuminate\Support\Str;

class PosRepo
{
    protected $modelGroup;

    public function __construct(
        MasterPos $model,
        group $group
        )
    {
        $this->model = $model;
        $this->modelGroup = $group;
    }
   
    public function getPos()
    {
        return $this->model->all();
    }
    public function findPosByEvent($information){
        return $this->model->where("information", $information)->get();
    }
    public function findAllGroup(){
        return $this->modelGroup->all();
    }
}
