<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use App\Service\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    #[Route('/identification', name: 'identification')]
    public function identification(UserPasswordEncoderInterface $passwordEncoder, SessionInterface $session)
    {

    }
    #[Route('/recuperarCuenta', name: 'recuperarCuenta')]
    public function recuperarCuenta(MailerInterface $mailer)
    {
    }
    #[Route('/crearUsuario', name: 'crearUsuario')]
    public function crearUsuario(MailerInterface $mailer, UserPasswordHasherInterface $passwordHasher)
    {
        if(isset($_POST['_username'],$_POST['usuario'],$_POST['_password'])){
            $email = preg_replace('/\s+/', '', $_POST['_username']);
            $username = substr(preg_replace('/\s+/', '', $_POST['usuario']), 0, 10);
            $password = $_POST['_password'];
            $user = $this->service->getUserByEMailOrUsername($email, $username);
            if(!$user){
                $user = $this->service->addUser($email,$username);
                $password = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($password);
                $this->service->addObject($user);
                $email = $this->service->createEmail($_SERVER,$user);
                $mailer->send($email);
            }
        }
        return $this->redirectToRoute('perfil');
    }
    #[Route('/recuperarClave/{codigo}', name: 'recuperarClave')]
    public function recuperarClave($codigo = 0, UserPasswordHasherInterface $passwordHasher)
    {
    }
    #[Route('/activarCuenta/{codigo}', name: 'activarCuenta')]
    public function activarCuenta($codigo = 0)
    {
    }
    #[Route('/darSeguir', name: 'darSeguir')]
    public function darSeguir(Request $request)
    {}
    #[Route('/filtrarUsuario', name: 'filtrarUsuario')]
    public function filtrarUsuarios(Request $request)
    {}
    #[Route('/actualizarUsuario', name: 'actualizarUsuario')]
    public function actualizarPerfil(Request $request)
    {}
    #[Route('/bloquearUsuario', name: 'bloquearUsuario')]
    public function bloquearUsuario(Request $request)
    {}
    #[Route('/hacerAdmin', name: 'hacerAdmin')]
    public function hacerAdmin(Request $request)
    {}
    #[Route('/quitarAdmin', name: 'quitarAdmin')]
    public function quitarAdmin(Request $request)
    {}
    #[Route('/actualizarFoto', name: 'actualizarFoto')]
    public function actualizarFoto(Request $request)
    {}
}
