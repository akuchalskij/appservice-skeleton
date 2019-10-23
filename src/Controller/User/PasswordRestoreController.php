<?php

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Entity\User\User;
use App\Exceptions\AppExpectedException;
use App\Exceptions\Error\ErrorMessage;
use App\Manager\User\UserManager;
use App\Manager\User\NotificationManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints;


/**
 * Class PasswordRestoreController
 * @package AppBundle\Controller\Api\User
 */
class PasswordRestoreController extends BaseController
{
    /**
     * @Rest\Post("/user/password/restore")
     * @Rest\RequestParam(name="token")
     * @Rest\RequestParam(name="email", requirements=@Constraints\Email)
     * @Rest\RequestParam(name="new_password")
     * @param ParamFetcher $paramFetcher
     * @param UserManager $userManager
     * @return Response
     * @throws AppExpectedException
     */
    public function passwordRestore(ParamFetcher $paramFetcher, UserManager $userManager): Response
    {
        $token = $paramFetcher->get('token');
        $email = $paramFetcher->get('email');
        $password = $paramFetcher->get('new_password');

        $userRepository = $this->getDoctrine()->getRepository(
          User::class
        );
        $user = $userRepository->findOneBy(['confirmationToken' => $token]);

        if (!$user instanceof User) {
            throw new AppExpectedException(
              ErrorMessage::USER_BAD_CREDENTIALS
            );
        }
        if ($user->getEmail() != $email) {
            throw new AppExpectedException(
                ErrorMessage::USER_BAD_CREDENTIALS
            );
        }

        $user->setPlainPassword($password);
        $userManager->removeConfirmationToken($user);

        return $this->createView(null, null, 200);
    }

    /**
     * @Rest\Post("/user/password/restore/link")
     *
     * @Rest\RequestParam(name="email", requirements=@Constraints\Email)
     * @param ParamFetcher $paramFetcher
     * @param NotificationManager $notificationManager
     * @return Response
     * @throws AppExpectedException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendPasswordRestoreLink(ParamFetcher $paramFetcher, NotificationManager $notificationManager): Response
    {
        $email = $paramFetcher->get('email');
        $userRepository = $this->getDoctrine()->getRepository(
          User::class
        );
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user instanceof User) {
            throw new AppExpectedException(
                ErrorMessage::USER_BAD_CREDENTIALS
            );
        }

        $notificationManager->sendUserPasswordRestoreNotifications($user);

        return $this->createView([], null, 200);
    }
}