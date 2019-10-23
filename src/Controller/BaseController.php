<?php
declare(strict_types=1);

namespace App\Controller;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BaseController
 * @package App\Controller
 */
class BaseController extends AbstractFOSRestController
{
    /**
     * @var ValidatorInterface $validator
     */
    protected $validator;

    /**
     * BaseController constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $entity
     */
    protected function validate($entity): void
    {
        $errors = $this->validator->validate($entity);
        if ($errors->count() > 0) {
            $errorMessage = '';
            foreach ($errors as $error) {
                $errorMessage .= $error->getMessage() . ' ';
            }
            throw new ValidatorException($errorMessage);
        }
    }

    /**
     * @param $data
     * @param array $groups
     * @param int $code
     * @return Response
     */
    protected function createView($data, array $groups = null, $code = Response::HTTP_OK): Response
    {
        $view = $this->view($data, $code);
        if (!is_null($groups)) {
            $context = new Context();
            $context->setGroups($groups);
            $view->setContext($context);
        }

        return $this->handleView($view);
    }
}