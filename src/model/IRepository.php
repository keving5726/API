<?php

namespace Src\Model;

interface IRepository
{
    public function findAll(): ?array;
    public function findBy(string $criteria, string $value): ?array;
}
