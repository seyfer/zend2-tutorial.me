<?php

namespace Users\Controller;

use Users\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Zend\Mail;

/**
 * Description of GroupChatController
 *
 * @author seyfer
 */
class GroupChatController extends BaseController
{

    public function sendMailAction()
    {
        $user = $this->getLoggedInUser();
        $form = $this->getServiceLocator()->get('SendMailForm');
        $form->initUsersSelect($user);

        if ($this->request->isPost()) {
            $post = $this->request->getPost();

//            \Zend\Debug\Debug::dump($post);
            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();

                $fromUserId = $user->getId();
                $toUserId   = $data['user'];
                $msgSubj    = $data['subject'];
                $msgText    = $data['message'];

                $result = $this->sendOfflineMessage($msgSubj, $msgText, $fromUserId, $toUserId);
            }
        }

        return array('form' => $form);
    }

    protected function sendOfflineMessage(
    $msgSubj, $msgText, $fromUserId, $toUserId)
    {
        $userTable = $this->getServiceLocator()->get('UserTable');
        $fromUser  = $userTable->getById($fromUserId);
        $toUser    = $userTable->getById($toUserId);

        $mail = new Mail\Message();
        $mail->setFrom($fromUser->getEmail(), $fromUser->getName());
        $mail->addTo($toUser->getEmail(), $toUser->getName());
        $mail->setSubject($msgSubj);
        $mail->setBody($msgText);

        $transport = new Mail\Transport\Sendmail();

//        $transport = new Mail\Transport\Smtp();
//        $options   = new Mail\Transport\SmtpOptions(array(
//            'name'              => 'smtp.gmail.com',
//            'host'              => 'smtp.gmail.com',
//            'connection_class'  => 'login',
//            'connection_config' => array(
//                'ssl'      => 'tls',
//                'username' => 'seyferseed@gmail.com',
//                'password' => '',
//            ),
//        ));
//        $transport->setOptions($options);
        $transport->send($mail);

        return true;
    }

    public function messageListAction()
    {
        $userTable     = $this->getServiceLocator()->get('UserTable');
        $chatMessageTG = $this->getServiceLocator()->get(
                'ChatMessagesTableGateway');

        $chatMessages = $chatMessageTG->select();

        $messageList = array();
        foreach ($chatMessages as $chatMessage) {
            $fromUser            = $userTable->getById($chatMessage->user_id);
            $messageData         = array();
            $messageData['user'] = $fromUser->getName();
            $messageData['time'] = $chatMessage->stamp;
            $messageData['data'] = $chatMessage->message;
            $messageList[]       = $messageData;
        }

        $viewModel = new ViewModel(array('messageList' => $messageList));
        $viewModel->setTemplate('users/group-chat/message-list');
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    protected function sendMessage($messageTest, $fromUserId)
    {

        $chatMessageTG = $this->getServiceLocator()
                ->get('ChatMessagesTableGateway');
        $data          = array(
            'user_id' => $fromUserId,
            'message' => $messageTest,
            'stamp'   => NULL
        );
        $chatMessageTG->insert($data);
        return true;
    }

    private function getLoggedInUser()
    {
        $authService = $this->getAuthService();
        $email       = $authService->getStorage()->read();

        if (!$email) {
            throw new \Exception("not auhorized");
        }

        $userTable = $this->getServiceLocator()->get('UserTable');
        $user      = $userTable->getUserByEmail($email);

        return $user;
    }

    public function indexAction()
    {
        $user    = $this->getLoggedInUser();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $messageTest = $request->getPost()->get('message');
            $fromUserId  = $user->getId();
            $this->sendMessage($messageTest, $fromUserId);
            // Для предотвращения дублирования записей при обновлении
            return $this->redirect()->toRoute('group-chat');
        }

        // Подготовка формы отправки сообщения
        $form = new \Zend\Form\Form();
        $form->add(array(
            'name'       => 'message',
            'attributes' => array(
                'type'     => 'text',
                'id'       => 'messageText',
                'required' => 'required'
            ),
            'options'    => array(
                'label' => 'Message',
            ),
        ));
        $form->add(array(
            'name'       => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Send'
            ),
        ));
        $form->add(array(
            'name'       => 'refresh',
            'attributes' => array(
                'type'  => 'button',
                'id'    => 'btnRefresh',
                'value' => 'Refresh'
            ),
        ));

        $viewModel = new ViewModel(array('form' => $form, 'userName' => $user->getName()));

        return $viewModel;
    }

}
