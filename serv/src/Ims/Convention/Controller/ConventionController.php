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
                $this->internshipAction($section);
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
        $adresses = $section['addresses'];
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
        foreach ($adresses as $adress){
            if ($adress['id'] == 'student_adress'){
                $student->address = $adress['value'];
            }
        }
        $student->save();
    }

    private function companyAction($section){
        $company = new CompanyModel();
        $inputs = $section['inputs'];
        $dropdowns = $section['dropdowns'];
        $adresses = $section['addresses'];
        foreach ($inputs as $input){
            switch ($input['id']){
                case 'ent_name' :
                    $company->name = $input['value'];
                    break;
                case 'ent_website' :
                    $company->website = $input['value'];
                    break;
                case 'ent_director_surname':
                    $company->director_surname = $input['value'];
                    break;
                case 'ent_director_name' :
                    $company->director_name = $input['value'];
                    break;
                case 'ent_director_email' :
                    $company->director_email = $input['value'];
                    break;
                case 'ent_director_phone' :
                    $company->director_phone = $input['value'];
                    break;
                case 'ent_director_quality' :
                    $company->director_quality = $input['value'];
                    break;
            }
        }
        foreach ($dropdowns as$dropdown){
            if($dropdown['id'] == 'ent_director_gender'){
                $company->director_gender = $dropdown['value'];
            }
        }
        /*foreach ($adresses as $adress){
            switch ($adress['id']){
                case 'ent_address' :
                    $company->address = $adress['value'];
                    break;
                case 'ent_stage_address' :
                    // TODO
                    break;
            }
        }*/
    }

    private function internshipAction($section){
        $internship = new InternshipModel();
        $inputs = $section['inputs'];
        $dropdowns = $section['dropdowns'];
        $textareas = $section['textareas'];
        foreach ($inputs as $input){
            switch ($input['id']){
                case 'internship_dos' :
                    $internship->start = $input['value'];
                    break;
                case 'internship_doe' :
                    $internship->end = $input['value'];
                    break;
                case 'internship_week_hours' :
                    $internship->working_hours = $input['value'];
                    break;
                case 'internship_remuneration' :
                    $internship->income = $input['value'];
                    break;
                case 'internship_title' :
                    $internship->subject = $input['value'];
                    break;
            }
        }
        foreach ($dropdowns as $dropdown){
            if($dropdown['id'] == 'internship_remuneration_way'){
                $internship->payement = $dropdown['value'];
            }
        }
        foreach ($textareas as$textarea){
            switch ($textarea['id']){
                case 'internship_hours_text' :
                    $internship->weekly_duration = $textarea['value'];
                    break;
                case 'internship_extras_text' :
                    $internship->extra_work = $textarea['value'];
                    break;
                case 'internship_advantages' :
                    $internship->advantages = $textarea['value'];
                    break;
                case 'internship_description' :
                    $internship->detail = $textarea['value'];
                    break;
            }
        }
    }

    private function responsablesAction($section){

    }

    private function supplementsAction($section){
        
    }
}
