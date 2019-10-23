<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Entity\User\User;
use App\Manager\User\UserManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends BaseController
{
    /**
     * @Rest\Get("/user/current")
     * @Rest\View(serializerGroups={"user"})
     *
     * @param UserManager $manager
     * @return Response
     */
    public function retrieve(UserManager $manager): Response
    {
        $user = $manager->getUser();

        if (!($user instanceof User)) {
            throw new AccessDeniedException();
        }

        return $this->createView($user, ['user'], 200);
    }
}
