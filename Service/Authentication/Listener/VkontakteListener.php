<?php
namespace Werkint\Bundle\SocialBundle\Service\Authentication\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;

/**
 * VkontakteListener.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class VkontakteListener extends AbstractAuthenticationListener
{
    protected function attemptAuthentication(Request $request)
    {
        echo 'test';
    }
    /*protected $social;

    public function setSocial(array $social)
    {
        $this->social = $social;
    }

    protected function checkRequest(Request $request)
    {
        die('test');
        $check = $request->get('uid') && $request->get('hash');
        $check = $check && $request->cookies->get('vk_app_' . $this->social['vk']['id']);
        return $check;
    }

    // метод attemptAuthentication производит всю работу, связанную с
    // извлечением из запроса информации, необходимой для создания Token’а
    public function attemptAuthentication(Request $request)
    {
        die('test');
        if ($this->checkRequest($request)) {
            $this->logger->debug('vk auth handled');

            // извлекаем информацию из запроса
            $uid = $request->get('uid');
            $fn = $request->get('first_name');
            $ln = $request->get('last_name');
            $hash = $request->get('hash');

            $this->logger->info("user $fn $ln [$uid] // $hash");

            $data = [
                'uid'    => $uid,
                'avatar' => [
                    'sav'  => $request->get('photo'),
                    'srav' => $request->get('photo_rec'),
                ],
                'name'   => $fn . ' ' . $ln,
            ];
            $token = new SocialToken(
                'vkontakte',
                $hash,
                $data
            );
            $token->setUser('vk' . $uid);

            // передаем токен на проверку
            return $this->authenticationManager->authenticate($token);
        }

        return null;
    }*/

}