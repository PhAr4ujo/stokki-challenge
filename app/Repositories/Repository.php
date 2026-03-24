<?php

namespace App\Repositories;

use App\Repositories\Errors\ModelNotDefined;
use App\Repositories\Interfaces\IRepository;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements IRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = app($this->model());
    }

    public function insert($data)
    {
        return $this->model->create($data);
    }

    public function edit($id, $data)
    {
        $model = $this->model->find($id);

        return $model->update($data);
    }

    public function delete($id)
    {
        try {
            $model = $this->model->find($id);

            return $model->delete();
        } catch (\Throwable $th) {
            if ($th->getCode() === '23000') {
                redirect()->back()->withErrors('Este registro não pode ser excluido!');
            }
        }
    }

    public function listRecords(array $filters, $paginationAmount)
    {
        $query = $this->model->query();

        foreach ($filters as $name => $value) {
            if ($value != null && $name != 'qtd_paginacao') {
                $query = $query->where($name, $value);
            }
        }

        $records = $query->paginate($paginationAmount);

        return $records;
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    abstract public function model();
}
