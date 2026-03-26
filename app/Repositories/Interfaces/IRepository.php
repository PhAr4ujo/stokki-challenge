<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IRepository
{
    public function listRecords(array $filters, int $paginationAmount): LengthAwarePaginator;
    public function insert($data);
    public function edit($id, $data);
    public function delete($id);
    public function find(int $id);

    public function getAll();

}
