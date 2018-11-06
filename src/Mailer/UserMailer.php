<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Filesystem\File;

class UserMailer extends Mailer
{


    public function admin($title, $contact, $settings_mail)
    {


        $this
            ->setFrom([$contact->email => $contact->name])
            ->setTo($settings_mail->email)
            ->setSubject($title)
            ->setTemplate('admin/contact','default')
            ->setEmailFormat('html')
            ->setViewVars([
                'title' => $title,
                'contact' => $contact,
            ]);

    }



    public function basic($title, $template, $data, $settings_mail)
    {

        $this
            ->setFrom([$settings_mail->email => 'Nom entreprise'])
            ->setTo([$data->email => $data->name])
            ->setSubject($title)
            ->setTemplate($template,'default')
            ->setEmailFormat('html')
            ->setViewVars([
                'title' => $title,
                $template => $data,
            ])
        ;

    }




}
