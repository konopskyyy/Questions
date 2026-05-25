<?php

declare(strict_types=1);

namespace App\User\UserInterface\Controller\Admin;

use App\User\Application\ResetPassword\ResetPasswordCommand;
use App\User\Domain\User;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;

class UserAdminController extends CRUDController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function resetPasswordAction(Request $request): Response
    {
        $user = $this->admin->getSubject();

        if (!$user instanceof User) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id: %s', $this->admin->getIdParameter()));
        }

        $form = $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'New Password'],
                'second_options' => ['label' => 'Repeat New Password'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $newPassword = $data['password'];

            $this->messageBus->dispatch(new ResetPasswordCommand($user->getEmail(), $newPassword));

            $this->addFlash('sonata_flash_success', 'Password reset successfully.');

            return $this->redirectToRoute('user_list');
        }

        return $this->renderWithExtraParams('admin/user/reset_password.html.twig', [
            'action' => 'reset_password',
            'form' => $form->createView(),
            'object' => $user,
        ]);
    }
}
