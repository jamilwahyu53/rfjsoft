<?php

namespace App\Repositories;

use App\Models\visitor;
use Illuminate\Support\Str;

class VisitorRepo
{
    protected $model;

    public function __construct(visitor $model)
    {
        $this->model = $model;
    }

    public function upsert(array $data, string $uniqueKey = 'phone')
    {
        $data["qr_code"] = (string) Str::uuid();
        return $this->model->updateOrCreate(
            [$uniqueKey => $data[$uniqueKey]], 
            $data
        );
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }
    public function findByPhone($phone)
    {
        return $this->model->where("phone", $phone)->count();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->model->find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }

    public function delete($id)
    {
        $user = $this->model->find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
