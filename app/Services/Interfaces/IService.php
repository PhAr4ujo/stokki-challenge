<?php

namespace App\Services\Interfaces;

interface IService
{
 
    public function listRecords(array $filters, int $paginationAmount);
    public function insert($data);
    public function edit($id, $data);
    public function delete($id);
    public function find(int $id);
}
