<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController
{
    /**
     * @var int HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param array $data
     * @param array $headers
     * @return JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return new JsonResponse(
            $data,
            $this->getStatusCode(),
            $headers
        );
    }

    /**
     * @param string $errors
     * @param array $headers
     * @return JsonResponse
     */
    public function respondWithErrors($errors, $headers = [])
    {
        $data = [
            'errors'    => $errors,
        ];

        return new JsonResponse(
            $data,
            $this->getStatusCode(),
            $headers
        );
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function respondUnauthorized($message = 'Not authorized')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function respondValidationError($message = 'Validation errors')
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function respondNotFound($message = 'Not found !')
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function respondCreated($data = [])
    {
        return $this->setStatusCode(201)->respond($data);
    }

    public function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if(json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        if($data === null) {
            return null;
        }

        $request->request->replace($data);

        return $request;
    }


    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return true;
    }
}
