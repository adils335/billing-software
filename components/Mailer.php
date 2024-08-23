<?php 
namespace app\components;

use Yii;
use yii\swiftmailer\Mailer as BaseMailer;

/**
 * Mailer.
 *
 * @author Mohd Qasim <mohd.qasim@leanport.info>
 */
class Mailer extends BaseMailer
{
    /** @var string */
    public $appViewPath = '@app/mail';
    public $appHtmlLayout = 'layouts/html'; 

    /** @var string|array Default: `Yii::$app->params['adminEmail']` OR `no-reply@example.com` */
    public $sender; 
    public $emailHeader = [];

    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array  $params
     *
     * @return bool
     */
    public function sendEmail($to, $subject, $view, $params = [])
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        $mailer->viewPath = $this->appViewPath;
        $mailer->htmlLayout = $this->appHtmlLayout;
        //$mailer->textLayout = $this->textLayout;
        $mailer->getView()->theme = Yii::$app->view->theme;
        if(!is_array($to)){
            $to = [$to];
        }

        if(!empty(Yii::$app->params['debug_email'])){
            $to[] = Yii::$app->params['debug_email'];
        }

        Yii::$app->params['emailHeader'] = $this->emailHeader;

        if ($this->sender === null) {
            $this->sender = isset(Yii::$app->params['adminEmail']) ? Yii::$app->params['adminEmail'] : 'no-reply@example.com';
        }

        return $mailer->compose(['html' => $view, /*'text' => 'text/' . $view*/], $params)
            ->setTo($to)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();
    }
}