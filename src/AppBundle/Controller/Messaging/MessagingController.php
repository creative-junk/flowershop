<?php

namespace AppBundle\Controller\Messaging;

use AppBundle\Entity\Message;
use AppBundle\Entity\Thread;
use AppBundle\Form\MessageFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessagingController extends Controller
{
    public function getTotalUnreadMessagesAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $totalUnread = 0;
        $em = $this->getDoctrine()->getManager();
        $nrUnread = $em->getRepository('AppBundle:Message')
            ->getNrUnread($user);

        $totalUnread += $nrUnread;


        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalUnread,

        ]);

    }
    public function getTotalUnreadNotificationsAction(){
        return 0;
    }
    public function getTotalUnreadAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $totalUnread = 0;

        $em = $this->getDoctrine()->getManager();
        $nrUnread = $em->getRepository('AppBundle:Message')
            ->getNrUnread($user);

        $totalUnread += $nrUnread;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalUnread,

        ]);
    }
    /**
     * @Route("/message/new",name="new-message")
     */
    public function newMessageAction(Request $request,$participantId=null){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $receiver = $em->getRepository("AppBundle:User")
            ->findOneBy([
                'id'=>$participantId
            ]);
        // var_dump($participant);exit;
        $thread = new Thread();
        $thread->setCreatedBy($user);
        $thread->setIsDeleted(false);


        $message = new Message();
        $message->setIsDeleted(false);
        $message->setThread($thread);
        $message->setIsRead(false);
        $message->setIsSpam(false);
        $message->setSender($user);


        $form = $this->createForm(MessageFormType::class,$message);

        $form->handleRequest($request);

        if ($form->isValid()&&$form->isSubmitted()){
            $message = $form->getData();
            $message->setSentAt(new \DateTime());

            $participant_id =  $request->request->get('participant');

            $participant = $em->getRepository("AppBundle:User")
                ->findOneBy([
                    'id'=>$participant_id
                ]);

            $thread->setLastMessage($message);
            $thread->setLastMessageDate(new \DateTime());
            $message->setParticipant($participant);

            $em->persist($thread);
            $em->persist($message);

            $em->flush();

            return new Response(null,204);
        }else{

        }
        return $this->render(':home/messages:new.htm.twig',[
            'messageForm'=>$form->createView(),
            'participant'=>$receiver
        ]);
    }
}
