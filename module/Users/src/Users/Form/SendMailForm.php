<?php

namespace Users\Form;

use Zend\Form\Element;
use Users\Form\BaseForm;

/**
 * Description of SendMailForm
 *
 * @author seyfer
 */
class SendMailForm extends BaseForm
{

    public function __construct()
    {
        parent::__construct(__CLASS__);
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $to = new Element\Text('to');
        $to->setLabel("Send To");
        $this->add($to);

        $subject = new Element\Text('subject');
        $subject->setLabel("Subject");
        $this->add($subject);

        $message = new Element\Textarea('message');
        $message->setLabel("Message");
        $this->add($message);

        $send = new Element\Submit('send');
        $send->setValue('Send');
        $this->add($send);
    }

    public function initUsersSelect($currentUser = null)
    {
        $users = $this->userTable->fetchAll();
        foreach ($users as $user) {
            if ($user->getEmail() != $currentUser->getEmail()) {
                $usersOptions[$user->getId()] = $user->getEmail();
            }
        }

        $options = array(
            'label'                     => 'Send to',
            'value_options'             => $usersOptions,
            'disable_inarray_validator' => true,
            "attributes"                => array(
                "value" => 0,
            )
        );

        $select = new Element\Select('user');
        $select->setOptions($options);
        $this->add($select);
    }

}
