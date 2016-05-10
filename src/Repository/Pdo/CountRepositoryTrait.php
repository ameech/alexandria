<?php

namespace Alexandria\Repository\Pdo;

trait CountRepositoryTrait
{
    /**
     * @param array $criteria
     * @return int
     */
    public function countBy(array $criteria)
    {
        $select = $this->withCriteria($this->select(['COUNT(*)']), $criteria);
        $statement = $this->execute($select);

        return $statement->fetchColumn();
    }
}
