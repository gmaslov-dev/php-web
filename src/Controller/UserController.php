<?php

namespace PhpWeb\Controller;

use PhpWeb\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class UserController extends BaseController
{
    private UserRepository $repository;
    public function __construct(Twig $renderer, UserRepository $repository)
    {
        parent::__construct($renderer);
        $this->repository = $repository;
    }


    public function index(Request $request, Response $response): Response
    {
        $limit = 5;
        $page = (int) $request->getQueryParam('page', 1);
        $currentPage = max(1, $page);

        $users = $this->repository->get($limit, (($currentPage - 1) * $limit));
        $totalPages = ceil(count($users) / $limit);

        return $this->view->render($response, 'users/index.twig', [
            'users' => $users,
            'page' => $currentPage,
            'pages' => $totalPages
        ]);
    }

//    public function new(Request $request, Response $response): Response
//    {
//        $router = RouteContext::fromRequest($request)->getRouteParser();
//        $success = $this->flash->getMessages()['success'] ?? [];
//
//        return $this->renderer->render($response, 'users/new.phtml', [
//            'userData' => [],
//            'errors' => [],
//            'router' => $router,
//            'success' => $success
//        ]);
//    }
//
//    public function create(Request $request, Response $response): Response
//    {
//        $validator = new \PhpWeb\UserValidator();
//        $userData = $request->getParsedBodyParam('user');
//
//        // TODO: refactor validation
//        $errors = $validator->validateEmpty($userData);
//        $errors = array_merge($errors, $validator->validateUniqEmail($userData['email'], $this->userDAO));
//
//        if (count($errors) === 0) {
//            $user = new \PhpWeb\User($userData['name'], $userData['email']);
//            $this->userDAO->save($user);
//            $this->flash->addMessage('success', 'User was added successfully');
//            return $response->withRedirect($this->getRouterUrl('users.new'));
//        }
//
//        return $this->renderer->render($response, 'users/new.phtml', [
//            'schoolData' => $userData,
//            'errors' => $errors
//        ])->withStatus(422);
//    }
//
//    public function edit(Request $request, Response $response, array $args): Response
//    {
//        $id = $args['id'];
//        $user = $this->userDAO->find($id);
//
//        return $this->renderer->render($response, 'users/edit.phtml', [
//            'userData' => [
//                'id' => $user->getId(),
//                'name' => $user->getName(),
//                'email' => $user->getEmail()
//            ],
//            'errors' => []
//        ]);
//    }

//    public function update(Request $request, Response $response, array $args): Response
//    {
//        $id = $args['id'];
//        $user = $this->userDAO->find($id);
//        $data = $request->getParsedBodyParam('user');
//
//        $validator = new \PhpWeb\UserValidator();
//        $errors = $validator->validateEmpty(['name' => $user->getName(), 'email' => $user->getEmail()], $this->userDAO);
//
//        if (count($errors) === 0) {
//            $user->setName($data['name']);
//            $user->setEmail($data['email']);
//            $this->userDAO->update($user);
//            $this->flash->addMessage('success', 'User was updated successfully');
//            return $response->withRedirect($this->getRouterUrl('user', ['id' => $id]));
//        }
//
//        return $this->renderer->render($response, 'users/edit.phtml', [
//            'userData' => ['id' => $id, ...$data],
//            'errors' => $errors
//        ])->withStatus(422);    public function show(Request $request, Response $response, array $args): Response
//    {
//        $id = $args['id'];
//        $user = $this->userDAO->find($id);
//
//        if (!$user) {
//            return $response->write('Page not found')->withStatus(404);
//        }
//
//        $success = $this->flash->getMessages()['success'] ?? [];
//
//        return $this->renderer->render($response, 'users/show.phtml', [
//            'user' => $user,
//            'success' => $success
//        ]);
//    }
//    }

//    public function show(Request $request, Response $response, array $args): Response
//    {
//        $id = $args['id'];
//        $user = $this->userDAO->find($id);
//
//        if (!$user) {
//            return $response->write('Page not found')->withStatus(404);
//        }
//
//        $success = $this->flash->getMessages()['success'] ?? [];
//
//        return $this->renderer->render($response, 'users/show.phtml', [
//            'user' => $user,
//            'success' => $success
//        ]);
//    }

//    private function getRouterUrl(string $name, array $params = []): string
//    {
//        $router = RouteContext::fromRequest($request)->getRouteParser();
//        return $router->urlFor($name, $params);
//    }
}