<?php

namespace Alexandria\Mapper;

abstract class AbstractMapper implements MapperInterface
{
    /**
     * @inheritDoc
     */
    public function mapObjects($rows)
    {
        return array_map(function ($row) {
            return $this->mapObject($row);
        }, $rows);
    }

    /**
     * @inheritDoc
     */
    abstract public function mapObject(array $row = []);
}
