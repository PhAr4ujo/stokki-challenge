<?php

namespace App\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IService
{
    public function listRecords(array $filters, int $paginationAmount): LengthAwarePaginator;
    public function insert($data);
    public function edit($id, $data);
    public function delete($id);
    public function find(int $id);
}
