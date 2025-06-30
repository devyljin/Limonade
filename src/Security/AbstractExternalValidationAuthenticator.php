<?php

namespace Agrume\Limonade\Security;

use Agrume\Limonade\Service\AuthService;
use App\Security\HypotheticUser;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

abstract class AbstractExternalValidationAuthenticator extends AbstractAuthenticator
{

    public function __construct(protected AuthService $authService, protected LoggerInterface $logger){}
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authHeader = $request->headers->get('Authorization');
        $apiToken = str_replace('Bearer ', '', $authHeader);

        if (!$apiToken) {
            throw new AuthenticationException('Token manquant');
        }

        $userData = $this->validateToken($apiToken);

        if (!$userData) {
            throw new AuthenticationException('Token invalide');
        }
        $passport =  new SelfValidatingPassport(
            new UserBadge($userData['login'], function () use ($userData) {
                return $this->getHypoteticUserPassport($userData['id'],$userData['login'], $userData['clientId'], $userData['trialDays'], $userData['roles'] ?? []);
            })
        );
        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(["message" => 'Échec authentification : ' . $exception->getMessage()], 401);
    }


    //    public function start(Request $request, ?AuthenticationException $authException = null): Response
    //    {
    //        /*
    //         * If you would like this class to control what happens when an anonymous user accesses a
    //         * protected page (e.g. redirect to /login), uncomment this method and make this class
    //         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
    //         *
    //         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
    //         */
    //    }
}
