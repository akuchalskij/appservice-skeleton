<?php

declare(strict_types=1);

namespace Upservice\Application\Query;

use Upservice\Domain\Core\Exception\NotFoundException;

class Collection
{
    /**
     * @var int
     */
    public int $page;

    /**
     * @var int
     */
    public int $limit;

    /**
     * @var int
     */
    public int $total;

    /**
     * @var Item[]
     */
    public array $data;

    /**
     * @param int $page
     * @param int $limit
     * @param int $total
     * @param array $data
     * @throws NotFoundException
     */
    public function __construct(int $page, int $limit, int $total, array $data)
    {
        $this->exists($page, $limit, $total);
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $total;
        $this->data = $data;
    }

    /**
     * @param int $page
     * @param int $limit
     * @param int $total
     * @throws NotFoundException
     */
    private function exists(int $page, int $limit, int $total): void
    {
        if (($limit * ($page - 1)) >= $total) {
            throw new NotFoundException();
        }
    }
}
