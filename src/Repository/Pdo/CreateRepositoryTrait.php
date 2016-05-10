<?php

namespace Alexandria\Repository\Pdo;

trait CreateRepositoryTrait
{
    /**
     * @inheritDoc
     */
    public function create(array $values)
    {
        return $this->find($this->insertAndGetId($values));
    }
}
