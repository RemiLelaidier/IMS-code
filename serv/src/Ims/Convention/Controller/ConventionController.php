<?php

namespace App\Ims\Convention\Controller;

use App\Core\Controller\Controller;
use App\Ims\Convention\Model\ConventionModel;
use App\Ims\Student\Model\StudentModel;
use App\Ims\Company\Model\CompanyModel;
use App\Ims\Internship\Model\InternshipModel;
use Slim\Http\Request;
use Slim\Http\Response;

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

        // Insert all Datas
        foreach ($decoded as $section){
           $this->doActionFor($section);
        }

        // Insert elements
        return $this->ok($response, [
            'convention_data' => $decoded['etudiant']['name']
        ]);
    }

    private function doActionFor($section){
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
                break;
            case 'Responsables' :
                $this->responsablesAction($section);
                break;
            case 'Informations complementaires' :
                $this->supplementsAction($section);
                break;
        }
    }

    /**
     *
     * @param $section
     */
    private function studentAction($section){
        $student = new StudentModel();
        $inputs = $section['inputs'];
        $dropdowns = $section['dropdowns'];
        foreach ($inputs as $input){
            switch ($input['id']){
                case 'student_name' :
                    $student->name = $input['value'];
                    break;
                case 'student_surname' :
                    $student->surname = $input['value'];
                    break;
                case 'student_ss' :
                    $student->ss = $input['value'];
                    break;
                case 'student_unice_number' :
                    $student->num = $input['value'];
                    break;
                case 'student_email' :
                    $student->email = $input['value'];
                    break;
                case 'student_dob' :
                    $student->dob = $input['value'];
                    break;
                case 'student_phone' :
                    $student->phone = $input['value'];
                    break;
                case 'student_insurance' :
                    $student->insurance = $input['value'];
                    break;
                case 'student_policy' :
                    $student->policy = $input['value'];
                    break;
            }
        }
        foreach ($dropdowns as $dropdown){
            switch ($dropdown['id']){
                case 'student_gender' :
                    $student->gender = $dropdown['value'];
                    break;
                case 'promotion' :
                    $student->promotion = $dropdown['value'];
                    break;
            }
        }
        $student->save();
    }

    private function conventionAction($data){

    }

    private function companyAction($section){

    }

    private function intershipAction($section){

    }

    private function responsablesAction($section){

    }

    private function supplementsAction($section){
        
    }
}
