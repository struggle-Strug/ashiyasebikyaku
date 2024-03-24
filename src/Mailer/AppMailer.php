<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Mailer\Email;

class AppMailer extends Mailer
{
    public function sendGridEmail($subject, $to, $templateName, $vars)
    {
        $this
            ->setEmailFormat('both')
            ->setFrom(['dev1@ashiyasebikyaku.sakura.ne.jp' => '脚痩せ美脚 リバースエイジングプログラム'])
            ->setTo($to)
            ->setSubject($subject)
            ->viewBuilder()
            ->setTemplate($templateName)
            ->setVars($vars);
    }
}
