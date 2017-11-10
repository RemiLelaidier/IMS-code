<?php

namespace App\Convention\Controller;

use App\Core\Controller\Controller;

use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

class ConventionController extends Controller
{
    public function submit(Request $request, Response $response)
    {
        $conventionData = $request->getBody()->getContents();

        $decoded = json_decode($conventionData, true);

        return $this->ok($response, [
            'convention_data' => $decoded
        ]);
    }
}
