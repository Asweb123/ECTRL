<?php
/**
 * Created by PhpStorm.
 * User: SIMON
 * Date: 15/03/2019
 * Time: 12:45
 */

namespace App\Service;


use App\Repository\UserRepository;
use Solvecrew\ExpoNotificationsBundle\Manager\NotificationManager;

class SendNotificationManager
{
    private $userRepository;
    private $notificationManager;

    public function __construct(UserRepository $userRepository, NotificationManager $notificationManager){
        $this->userRepository = $userRepository;
        $this->notificationManager = $notificationManager;
    }

    public function sendNotification($sender)
    {
        $usersCompany = $this->userRepository->findBy(['company' => $sender->getCompany(), 'userEnable' => true]);

        if($sender->getRole()->getRank() === 3){
            foreach($usersCompany as $user){
                $superiorUsers = [];
                if($user->getRole()->getRank() === 2){
                    $superiorUsers[] = $user;
                    return $superiorUsers;
                }
            }

            $title = 'ECTRL - Nouvel Audit validé !';
            $message = 'Rendez-vous sur l\'application ECTRL pour valider les résultats de l\'Audit réalisé par: '.$sender->getFirstName().' '.$sender->getLastName().'.';
        }

        if($sender->getRole()->getRank() === 2){
            foreach($usersCompany as $user){
                $superiorUsers = [];
                if($user->getRole()->getRank() === 1){
                    $superiorUsers[] = $user;
                    return $superiorUsers;
                }
            }

            $title = 'ECTRL - Nouvel Audit achevé !';
            $message = 'Rendez-vous sur l\'application ECTRL pour visualiser les résultats de l\'Audit validé par: '.$sender->getFirstName().' '.$sender->getLastName().'.';

        }

        foreach($superiorUsers as $superiorUser){
            foreach($superiorUser->getExpoPushTokens as $expoPushToken){
                $this->notificationManager->sendNotification($title, $message, $expoPushToken);
            }
        }

    }

}