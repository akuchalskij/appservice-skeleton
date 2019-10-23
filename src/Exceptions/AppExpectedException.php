<?php
declare(strict_types=1);

namespace App\Exceptions;


/**
 * Class AppExpectedException
 */
class AppExpectedException extends \Exception implements AppExceptionInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * AppExpectedException constructor.
     * @param string $message
     * @param int $code
     * @param array $data
     */
    public function __construct($message = '', $code = 500, array $data = [])
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    /**
     * @inheritdoc
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function addData(string $key, string $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Set error code
     *
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }
}
