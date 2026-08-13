<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            $isAjax = ($request instanceof IncomingRequest && $request->isAJAX());
            
            // If request expects JSON or is AJAX
            if ($isAjax || str_contains($request->getHeaderLine('Accept'), 'application/json')) {
                return service('response')->setJSON([
                    'status'  => 'error',
                    'message' => 'Silakan login terlebih dahulu untuk mengakses fitur ini.',
                ])->setStatusCode(401);
            }

            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
