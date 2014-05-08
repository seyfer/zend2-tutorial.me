<?php

namespace Users\Controller;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Description of Html5TestController
 *
 * @author seyfer
 */
class Html5TestController extends BaseController
{

    public function indexAction()
    {
        $form     = new Form();
        // Элемент Date/Time
        $dateTime = new Element\DateTime('element-date-time');
        $dateTime->setLabel('Date/Time Element')->setAttributes(array(
            'min'  => '2000-01-01T00:00:00Z',
            'max'  => '2020-01-01T00:00:00Z',
            'step' => '1',
        ));
        $form->add($dateTime);
        // Элемент Date/Time Local
        $dateTime = new Element\DateTimeLocal('element-date-time-local');
        $dateTime->setLabel('Date/Time Local Element')->setAttributes(array(
            'min'  => '2000-01-01T00:00:00Z',
            'max'  => '2020-01-01T00:00:00Z',
            'step' => '1',
        ));
        $form->add($dateTime);
        // Элемент Time
        $time     = new Element\Time('element-time');
        $time->setLabel('Time Element');
        $form->add($time);
        // Элемент Date
        $date     = new Element\Date('element-date');
        $date->setLabel('Date Element')->setAttributes(array(
            'min'  => '2000-01-01',
            'max'  => '2020-01-01',
            'step' => '1',
        ));
        $form->add($date);
        // Элемент Week
        $week     = new Element\Week('element-week');
        $week->setLabel('Week Element');
        $form->add($week);
        // Элемент Month
        $month    = new Element\Month('element-month');
        $month->setLabel('Month Element');
        $form->add($month);
        // Элемент Email
        $email    = new Element\Email('element-email');
        $email->setLabel('Email Element');
        $form->add($email);
        // Элемент URL
        $url      = new Element\Url('element-url');
        $url->setLabel('URL Element');
        $form->add($url);
// Элемент Number
//        $number   = new Element\Number('element-number');
//        $number->setLabel('Number Element');
//        $form->add($number);
// Элемент Range
//        $range    = new Element\Range('element-range');
//        $range->setLabel('Range Element');
//        $form->add($range);
// Элемент Color
        $color    = new Element\Color('element-color');
        $color->setLabel('Color Element');
        $form->add($color);

        return array('form' => $form);
    }

}
