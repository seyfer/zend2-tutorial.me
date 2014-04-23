<?php

namespace Application\Session\Service;

use Zend\Session\Service\SessionManagerFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\SessionManager,
    Zend\Session\Container;

/**
 * Description of AppSessionManagerFactory
 *
 * @author seyfer
 */
class AppSessionManagerFactory extends SessionManagerFactory {

    public function createService(ServiceLocatorInterface $sm)
    {
        $config = $sm->get('config');

        if (isset($config['session'])) {
            $session = $config['session'];

            $sessionConfig = null;
            //если есть ключ, проверить базовые настройки
            if (isset($session['config'])) {
                $class = isset($session['config']['class']) ?
                        $session['config']['class'] : 'Zend\Session\Config\SessionConfig';

                $options = isset($session['config']['options']) ?
                        $session['config']['options'] : array();

                $sessionConfig = new $class();
                $sessionConfig->setOptions($options);
            }

            //настройка типа хранилища
            $sessionStorage = null;
            if (isset($session['storage'])) {
                $class          = $session['storage'];
                $sessionStorage = new $class();
            }

            //не настроено
            $sessionSaveHandler = null;
            if (isset($session['save_handler'])) {
                // class should be fetched from service manager since
                // it will require constructor arguments
                $sessionSaveHandler = $sm->get($session['save_handler']);
            }

            $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);

            //применить валидаторы
            if (isset($session['validators'])) {
                $chain = $sessionManager->getValidatorChain();

                foreach ($session['validators'] as $validator) {
                    $validator = new $validator();
                    $chain->attach('session.validate', array($validator, 'isValid'));
                }
            }
        }
        else {
            //если нет конфига - возвращаем по умолчанию
//            $sessionManager = new SessionManager();
            $sessionManager = parent::createService($services);
        }

        //установка для контейнеров
        Container::setDefaultManager($sessionManager);

        return $sessionManager;
    }

}
