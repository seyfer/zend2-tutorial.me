<?php

namespace Users\Controller;

use Users\Controller\BaseController;
use Zend\View\Model\ViewModel;

/**
 * Description of GroupChatController
 *
 * @author seyfer
 */
class GroupChatController extends BaseController
{

    public function messageListAction()
    {
        $userTable     = $this->getServiceLocator()->get('UserTable');
        $chatMessageTG = $this->getServiceLocator()->get(
                'ChatMessagesTableGateway');

        $chatMessages = $chatMessageTG->select();

        $messageList = array();
        foreach ($chatMessages as $chatMessage) {
            $fromUser            = $userTable->getUser($chatMessage->user_id);
            $messageData         = array();
            $messageData['user'] = $fromUser->name;
            $messageData['time'] = $chatMessage->stamp;
            $messageData['data'] = $chatMessage->message;
            $messageList[]       = $messageData;
        }

        $viewModel = new ViewModel(array('messageList' => $messageList));
        $viewModel->setTemplate('users/group-chat/message-list');
        $viewModel->setTerminal(true);

        return $viewModel;
    }

}
