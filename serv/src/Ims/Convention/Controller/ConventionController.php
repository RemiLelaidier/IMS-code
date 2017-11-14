<?php

namespace App\Ims\Convention\Controller;

use App\Core\Controller\Controller;
use App\Ims\Convention\Model\ConventionModel;
use App\Ims\Student\Model\StudentModel;
use App\Ims\Company\Model\CompanyModel;
use App\Ims\Internship\Model\InternshipModel;
use Slim\Http\Request;
use Slim\Http\Response;

use App\Core\Generator\DocumentGenerator;

class ConventionController extends Controller
{

    /**
     * Method submit
     * Put all the datas received from the four stages of filling the convention in the database
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function submit(Request $request, Response $response){
        // Decode datas
        $conventionData = $request->getBody()->getContents();
        $decoded = json_decode($conventionData, true);
        // Log activity
        $this->logger->debug('New convention on ' . get_class($this) . ":submit", [
            'data' => $conventionData
        ]);

        $pdfGenerator = new DocumentGenerator($decoded);
        $pdfGenerator->generateConvention();

        // TODO Rémi : Debug before push on master :p
        // Insert all Datas
        /*foreach ($decoded as $section){
           $this->doActionFor($section);
        }*/

        // Insert elements
        return $this->ok($response, [
            'convention_data' => $decoded
        ]);
    }

    public function doActionFor($section){
        $name = $section['title'];
        switch($name){
            case 'Étudiant' :
                $this->studentAction($section);
                break;
            case 'Entreprise' :
                $this->companyAction($section);
                break;
            case 'Stage' :
                $this->intershipAction($section);
        }
    }

    public function studentAction($section){
        $student = new StudentModel();
    }

    public function conventionAction($section){

    }

    public function companyAction($section){

    }

    public function internshipAction($section){

    }
}
