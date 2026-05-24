<?php

namespace App\Repositories;

use App\Models\masterstaff;
use Illuminate\Support\Str;

class AdminRepo
{
    protected $model;

    public function __construct(masterstaff $model)
    {
        $this->model = $model;
    }
    public function findByPhone($phone)
    {
        return $this->model->where("Phone", $phone)->count();
    }

    public function upsert(array $data, string $uniqueKey = 'Phone')
    {
        $data["Kode"] = (string) Str::uuid();
        return $this->model->updateOrCreate(
            [$uniqueKey => $data[$uniqueKey]], 
            $data
        );
    }
    public function findByUserAndPass($data): ?masterstaff 
    {
        return $this->model->where("password", $data["password"])->where("StaffName",$data["StaffName"])->first();
    }
    public function findByPhonePass($data): ?masterstaff 
    {
        return $this->model->where("password", $data["password"])->where("phone",$data["phone"])->first();
    }

    
}
