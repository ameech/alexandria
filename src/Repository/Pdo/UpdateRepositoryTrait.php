<?php

namespace Alexandria\Repository\Pdo;

use Equip\Data\Traits\DiffableTrait;
use DomainException;

trait UpdateRepositoryTrait
{
    use DiffableTrait;

    /**
     * @param integer $id
     * @param array $values
     *
     * @return \Equip\Data\EntityInterface
     */
    public function update($id, array $values)
    {
        $pdo = $this->getWriteConnection();
        $pdo->beginTransaction();
        try {
            $current = $this->find($id);
            $diff = $this->diff($current, $values);
            if ($diff) {
                $update = $this->query
                    ->newUpdate()
                    ->table($this->getTable())
                    ->cols($diff)
                    ->where('id = ?', $id);
                $this->execute($update);
                $current = $this->find($id);
            }
        } catch (DomainException $e) {
            $pdo->rollback();
            throw $e;
        }
        $pdo->commit();
        return $current;
    }
}
