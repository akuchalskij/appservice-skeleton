<?php
declare(strict_types=1);

namespace App\Controller\User;


use App\Controller\BaseController;
use App\Exceptions\AppExpectedException;
use App\Exceptions\Error\ErrorMessage;
use App\Manager\User\UserManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints;

/**
 * Class RegisterController
 * @package App\Controller\User
 */
class RegisterController extends BaseController
{
    /**
     * @Rest\Post("/user/register")
     * @SWG\Response(
     *     response="200",
     *     description="Register new user"
     * )
     * @Rest\RequestParam(name="email", requirements=@Constraints\Email)
     * @Rest\RequestParam(name="password")
     * @param ParamFetcher $paramFetcher
     * @param UserManager $userManager
     * @return Response
     * @throws AppExpectedException
     */
    public function register(ParamFetcher $paramFetcher, UserManager $userManager): Response
    {
        $username = $email = $paramFetcher->get('email');
        $password = $paramFetcher->get('password');

        if ($userManager->userExists($email)) {
            throw new AppExpectedException(ErrorMessage::USER_EMAIL_IS_EXIST);
        }
        
        $user = $userManager->create($username, $password, $email, true, false);
        $token = $userManager->getToken($user);

        return $this->createView(["token" => $token], null, 200);
    }
} 