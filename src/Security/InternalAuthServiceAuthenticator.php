<?php

namespace Agrume\Limonade\Security;

use Agrume\Limonade\Mediator\MediatorEvent\TokenValidationMediatorEvent;
use Agrume\Limonade\Security\AbstractExternalValidationAuthenticator;
use Agrume\Limonade\Security\HypotheticUser;
use Agrume\Limonade\Service\ServiceLoggerMediator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class  InternalAuthServiceAuthenticator extends AbstractExternalValidationAuthenticator
{
    protected function getHypoteticUserPassport(...$args)
    {
        return new HypotheticUser(...$args);
    }

    protected function validateToken(string $token): ?array
    {
        $validationTokenEndpoint = '/api/validate-token';
        try {
            $request = Request::create(
                $validationTokenEndpoint, // URI (relative à la racine du site)
                'GET',   // Méthode HTTP
                [],      // Paramètres ($_GET)
                [],      // Cookies
                [],      // Fichiers
                [
                    'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
                ]
            );
            $service = $this->authService->setMediator(new ServiceLoggerMediator($this->authService, $this->logger));
            $service->getMediator()->nautofy(new TokenValidationMediatorEvent($service));
            $response = $service->setEndpoint($validationTokenEndpoint)->processRequest($request);

        } catch (\Exception $exception){
            throw new AuthenticationException("Token invalide");
        }
        if ($response->getStatusCode() !== 200) {
            return null;
        }
        $parsedJsonResponse = json_decode($response->getContent(), true);
        if(true === $parsedJsonResponse["valid"]){
            return $parsedJsonResponse["user"]; // attend un tableau contenant au minimum 'email' soit l'userIdentifier
        } else {
            throw new AuthenticationException("Token invalide");
        }
    }

}
