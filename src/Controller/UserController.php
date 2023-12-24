<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Service;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    #[Route('/identification', name: 'identification')]
    public function identification(SessionInterface $session, UserPasswordHasherInterface $passwordHasher)
    {
        if (isset($_POST['_username'], $_POST['_password'])) {
            $username = trim(strtolower($_POST['_username']));
            $password = $_POST['_password'];
            $user_email = $this->service->getUserByEmail($username);
            $user_username = $this->service->getUserByUsername($username);
            if ($user_email || $user_username) {
                $user = $user_email ? $user_email : $user_username;
                if (!$passwordHasher->isPasswordValid($user, $password)) {
                    $session->getFlashBag()->add('error', "Contraseña incorrectas");
                } else if ($user->getBloquear()) {
                    $session->getFlashBag()->add('error', "Cuenta bloqueada");
                } else if (!$user->getActivado()) {
                    $session->getFlashBag()->add('error', "Cuenta no activada");
                } else {
                    $this->setToken($user);
                }
            } else {
                $session->getFlashBag()->add('error', "Usuario no encontrado");
            }
        }
        return $this->redirectToRoute('perfil');
    }
    #[Route('/recuperarCuenta', name: 'recuperarCuenta')]
    public function recuperarCuenta(MailerInterface $mailer, SessionInterface $session)
    {
        if (isset($_POST['_username'])) {
            $username = trim(strtolower($_POST['_username']));
            $user_email = $this->service->getUserByEmail($username);
            $user_username = $this->service->getUserByUsername($username);
            if ($user_email || $user_username) {
                $user = $user_email ? $user_email : $user_username;
                $email = $this->service->emailRecuperateUser($user, $_SERVER);
                $mailer->send($email);
                $this->service->updateObject();
                $session->getFlashBag()->add('error', "Revisa tu correo: " . $user->getEmail());
            } else {
                $session->getFlashBag()->add('error', "Usuario no encontrado");
            }
        }
        return $this->redirectToRoute('perfil');
    }
    #[Route('/crearUsuario', name: 'crearUsuario')]
    public function crearUsuario(MailerInterface $mailer, UserPasswordHasherInterface $passwordHasher, SessionInterface $session)
    {

        if (isset($_POST['_username'], $_POST['usuario'], $_POST['_password'])) {
            $email = trim(strtolower($_POST['_username']));
            $username = trim(strtolower($_POST['usuario']));
            $username = substr($username, 0, 12);
            $password = $_POST['_password'];
            $user_email = $this->service->getUserByEmail($email);
            $user_username = $this->service->getUserByUsername($username);
            if (!$user_email && !$user_username) {
                $user = $this->service->addUser($email, $username);
                $password = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($password);
                $this->service->addObject($user);
                $email = $this->service->emailCreateUser($_SERVER, $user);
                $mailer->send($email);
                $session->getFlashBag()->add('error', "Revisa tu correo: " . $user->getEmail());
            } else if ($user_email) {
                $session->getFlashBag()->add('error', "Ya existe una cuenta con " . $email);
            } else {
                $session->getFlashBag()->add('error', "Ya existe una cuenta con " . $username);
            }
        }
        return $this->redirectToRoute('perfil');
    }


    #[Route('/recuperarCuenta/{codigo}', name: 'recuperarClave')]
    public function recuperarClave($codigo, UserPasswordHasherInterface $passwordHasher, SessionInterface $session)
    {
        $user = $this->getUser();
        if ($user && isset($_POST['_password'])) {
            $password = $_POST['_password'];
            $password = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($password);
            $this->service->updateObject();
        } else if (!$user) {
            $user = $this->service->getUserByRecuperar($codigo);
            if (new DateTime() < $user->getCaducidad()) {
                $user->setRecuperar(0);
                $user->setActivado(1);
                $this->setToken($user);
                $this->service->updateObject();
                return $this->render('cambiarClave.html.twig');
            } else {
                $session->getFlashBag()->add('error', "Enlace caducado");
            }
        }
        return $this->redirectToRoute('perfil');
    }
    #[Route('/activarCuenta/{codigo}', name: 'activarCuenta')]
    public function activarCuenta($codigo)
    {
        $user = $this->service->getUserByRecuperar($codigo);
        if ($user && new DateTime() < $user->getCaducidad()) {
            $user->setRecuperar(0);
            $user->setActivado(1);
            $this->setToken($user);
            $this->service->updateObject();
        }
        return $this->redirectToRoute('perfil');
    }
    #[Route('/darSeguir', name: 'darSeguir')]
    public function darSeguir(Request $request)
    {
        $js['respuesta'] = false;
        $myUser = $this->getUser();
        if (!$myUser) return new JSONResponse($js);
        $id = $request->request->get('codigo');
        $user = $this->service->getUserById($id);
        if ($user) {
            $js['respuesta'] = true;
            $siguiendo = $this->service->getSiguiendo($user, $myUser);
            if ($siguiendo) {
                $this->service->deleteObject($siguiendo);
                $js['tipo'] = 0;
            } else {
                $siguiendo = $this->service->addSiguiendo($user, $myUser);
                $this->service->addObject($siguiendo);
                $js['tipo'] = 1;
            }
        }
        return new JSONResponse($js);
    }
    #[Route('/filtrarUsuario', name: 'filtrarUsuario')]
    public function filtrarUsuarios(Request $request)
    {
        $js = [];
        $user = $this->getUser();
        if ($user && $request->isXmlHttpRequest() && $this->isGranted('ROLE_ADMIN')) {
            $username = $request->request->get('nombre');
            $myId = $user->getId();
            if ($username) {
                $users = $this->service->getUsersLikeUsername($username, $myId);
            } else {
                $users = $this->service->getAllUser($myId);
            }
            foreach ($users as $user) {
                $js[] = ['codigo' => $user->getId(), 'usuario' => $user->getUsername(), 'bloquear' => $user->getBloquear() ? $user->getBloquear() : 0, 'rol' => $user->getRol() ? $user->getRol() : 0, 'foto' => $user->getFoto() ? $user->getFoto() : ""];
            }
        }
        return new JsonResponse($js);
    }
    #[Route('/actualizarUsuario', name: 'actualizarUsuario')]
    public function actualizarPerfil(Request $request)
    {
        $js['respuesta'] = false;
        $user = $this->getUser();
        if (!$user) return new JSONResponse($js);
        $username = preg_replace('/\s+/', '', substr(trim($request->request->get('username')), 0, 10));
        $other_exist = $this->service->getUserByUsername($username);
        if (!$other_exist || ($other_exist && $other_exist->getId() == $user->getId())) {
            $user->setUsername($username);
            $js = ['respuesta' => true];
            $this->service->updateObject();
        }
        return new JsonResponse($js);
    }
    #[Route('/bloquearUsuario', name: 'bloquearUsuario')]
    public function bloquearUsuario(Request $request)
    {
        $js['respuesta'] = false;
        $user = $this->getUser();
        if (!$user ||  !$this->isGranted('ROLE_ADMIN')) return new JSONResponse($js);
        $user = $request->request->get('codigo');
        $user = $this->service->getUserById($user);
        if ($user) {
            if ($user->getBloquear()) $user->setBloquear(0);
            else $user->setBloquear(1);
            $this->service->updateObject();
            $js['respuesta'] = true;
            $js['tipo'] = $user->getBloquear();
        }
        return new JsonResponse($js);
    }
    #[Route('/hacerAdmin', name: 'hacerAdmin')]
    public function hacerAdmin(Request $request)
    {
        $js['respuesta'] = false;
        $user = $this->getUser();
        if (!$user ||  !$this->isGranted('ROLE_ADMIN')) return new JSONResponse($js);
        $user = $request->request->get('codigo');
        $tipo = $request->request->get('tipo');
        $user = $this->service->getUserById($user);
        if ($user) {
            $user->setRol($tipo ? 2 : 1);
            $this->service->updateObject();
            $js['respuesta'] = true;
            $js['tipo'] = $user->getRol();
        }
        return new JsonResponse($js);
    }
    #[Route('/quitarAdmin', name: 'quitarAdmin')]
    public function quitarAdmin(Request $request)
    {
        $js['respuesta'] = false;
        $user = $this->getUser();
        if (!$user ||  !$this->isGranted('ROLE_ADMIN')) return new JSONResponse($js);
        $user = $request->request->get('codigo');
        $user = $this->service->getUserById($user);
        if ($user) {
            $user->setRol(0);
            $this->service->updateObject();
            $js['respuesta'] = true;
        }
        return new JsonResponse($js);
    }
    #[Route('/actualizarFoto', name: 'actualizarFoto')]
    public function actualizarFoto(Request $request)
    {
        $js['actualizado'] = false;
        $user = $this->getUser();
        if (!$user) return new JSONResponse($js);
        $js['actualizado'] = true;
        $file = $request->files->get('file');
        $file_name = $this->guardarFoto($user, $file);
        $user->setFoto($file_name);
        $this->service->updateObject();
        return new JsonResponse($js);
    }

    private function setToken(User $user)
    {
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->container->get('security.token_storage')->setToken($token);
        //$this->container->get('session')->set('_security_main', serialize($token));
    }
    private function guardarFoto(User $user, $file)
    {
        $filesystem = new Filesystem();
        $folderPath = $this->getParameter('kernel.project_dir') . '/public/Usuario/u' . $user->getId() . '/';
        if (!$filesystem->exists($folderPath)) {
            $filesystem->mkdir($folderPath, 0777, true);
        } else {
            $files = glob($folderPath . '*', GLOB_MARK | GLOB_NOSORT);
            // Eliminar cada elemento individualmente
            foreach ($files as $existingFile) {
                $filesystem->remove($existingFile);
            }
        }
        $file->move($folderPath, $file->getClientOriginalName());
        return $file->getClientOriginalName();
    }
}
