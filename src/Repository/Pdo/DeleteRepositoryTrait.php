<?php

use Alexandria\Repository\Pdo;

use Aura\SqlQuery\Common\DeleteInterface;
use Aura\SqlQuery\Common\LimitInterface;
use Aura\SqlQuery\Common\OrderByInterface;

trait DeleteRepositoryTrait
{
    /**
     * @inheritDoc
     */
    public function delete($id)
    {
        return $this->deleteBy(['id' => $id]);
    }

    /**
     * {@inheritDoc}
     *
     * @throws RuntimeException if database server does not support LIMIT
     *         clauses in DELETE statements
     */
    public function deleteOneBy(array $criteria)
    {
        return $this->deleteBy($criteria, null, 1);
    }

    /**
     * @inheritDoc
     */
    public function deleteBy(array $criteria, array $order_by = null, $limit = null)
    {
        $delete = $this->query
            ->newDelete()
            ->from($this->getTable());

        $this->applyOrderBy($delete, $order_by);
        $this->applyLimit($delete, $limit);
        $this->applyCriteria($delete, $criteria);
        $stmt = $this->execute($delete);

        return (bool) $stmt->rowCount();
    }

    /**
     * @param DeleteInterface $delete
     * @param array|null $order_by
     * @throws RuntimeException if database server does not support ORDER BY
     *         clauses in DELETE statements
     */
    private function applyOrderBy(DeleteInterface $delete, array $order_by = null)
    {
        if (!$order_by) {
            return;
        }

        if (!$delete instanceof OrderByInterface) {
            throw new RuntimeException('Server does not support ORDER BY in DELETE statements');
        }

        $delete->orderBy($order_by);
    }

    /**
     * @param DeleteInterface $delete
     * @param int|null $limit
     * @throws RuntimeException if database server does not support LIMIT
     *         clauses in DELETE statements
     */
    private function applyLimit(DeleteInterface $delete, $limit = null)
    {
        if (!$limit) {
            return;
        }

        if (!$delete instanceof LimitInterface) {
            throw new RuntimeException('Server does not support LIMIT in DELETE statements');
        }

        $delete->limit($limit);
    }

    /**
     * @param DeleteInterface $delete
     * @param array $criteria
     */
    private function applyCriteria(DeleteInterface $delete, array $criteria)
    {
        foreach ($criteria as $key => $value) {
            $delete->where($key . ' = ?', $value);
        }
    }
}
