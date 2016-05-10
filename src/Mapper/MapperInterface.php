<?php

namespace Alexandria\Mapper;

interface MapperInterface
{
    /**
     * @param array|Traversable $rows
     * @return Entity[]
     */
    public function mapObjects($rows);

    /**
     * @param array $row
     * @return Entity
     */
    public function mapObject(array $row = []);
}
