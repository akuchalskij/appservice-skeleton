<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Exceptions\AppExpectedException;
use App\Entity\User\User;
use App\Exceptions\Error\ErrorMessage;
use App\Manager\User\UserManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

/**
 * Class LoginController
 * @package App\Controller\User
 */
class LoginController extends BaseController
{
    /**
     * @Rest\Post("/user/login")
     * @SWG\Response(
     *     response="200",
     *     description="Login"
     * )
     * @Rest\RequestParam(name="email", requirements=@Constraints\Email)
     * @Rest\RequestParam(name="password")
     * @param UserManager $userManager
     * @param ParamFetcher $paramFetcher
     * @return Response
     * @throws AppExpectedException
     */
    public function login(UserManager $userManager, ParamFetcher $paramFetcher): Response
    {
        
        $email = $paramFetcher->get('email');
        $password = $paramFetcher->get('password');

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!($user instanceof User)) {
            throw new AppExpectedException(ErrorMessage::USER_BAD_CREDENTIALS);
        }

        if (!$userManager->isPasswordValid($user, $password)) {
            throw new AppExpectedException(ErrorMessage::USER_BAD_CREDENTIALS);
        }

        if (!$user->isEnabled()) {
            throw new AppExpectedException(ErrorMessage::USER_PROFILE_DISABLED);
        }

        $token = $userManager->getToken($user);

        return $this->createView(["token" => $token], null, 200);
    }
}