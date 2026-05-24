<?php

namespace App\Repositories;

use App\Models\masterSidebar;
use Illuminate\Support\Str;

class SidebarRepo
{
    protected $model;

    public function __construct(masterSidebar $model)
    {
        $this->model = $model;
    }
    public function findByCompany($company)
    {
        return $this->model->where("Company", $company)->get();
    }
}
