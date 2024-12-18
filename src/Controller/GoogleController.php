<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionFactoryInterface;

use Google\Client;
use Google_Service_Oauth2;

class GoogleController extends AbstractController
{
    private SessionInterface $session;
    private const GOOGLE_CLIENT_ID = '669555723831-dpmf3m9pgqmblhchjvfo2pgsq5r361j1.apps.googleusercontent.com';
    private const GOOGLE_CLIENT_SECRET = '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';
    


    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/google-auth", name="google_auth")
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $client = new Client();
        $client->setApplicationName('Client Web 1');
        $googleClientId = $this->getParameter('google_client_id');
        $googleClientSecret = $this->getParameter('google_client_secret');
         // Valeur directe
        $client->setRedirectUri($this->generateUrl('google_auth', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $client->addScope(\Google\Service\Oauth2::USERINFO_EMAIL);

        // Handle OAuth2 response
        if ($request->query->has('code')) {
            $client->fetchAccessTokenWithAuthCode($request->query->get('code'));
            $accessToken = $client->getAccessToken();

            if (isset($accessToken['error'])) {
                $this->addFlash('error', 'OAuth Error: ' . $accessToken['error_description']);
                return $this->redirectToRoute('app_login');
            }

            $this->session->set('google_access_token', $accessToken);
            return $this->redirectToRoute('app_home');
        }

        // Redirect to Google for authentication
        $authUrl = $client->createAuthUrl();
        return new RedirectResponse($authUrl);
    }
}
