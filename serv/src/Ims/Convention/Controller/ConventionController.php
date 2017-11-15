<?php

namespace App\Ims\Convention\Controller;

use App\Core\Controller\Controller;
use App\Ims\Convention\Model\ConventionModel;
use App\Ims\Student\Model\StudentModel;
use App\Ims\Company\Model\CompanyModel;
use App\Ims\Internship\Model\InternshipModel;
use App\Ims\Unice\Model\UniceModel;
use App\Ims\Employee\Model\EmployeeModel;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Core\Generator\DocumentGenerator;

class ConventionController extends Controller
{
    private $studentModel;
    private $companyModel;
    private $uniceModel;
    private $employeeModel;
    private $internshipModel;

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

        /*foreach($decoded as $section){
            $inputs = $section['inputs'];
            $addresses = $section['addresses'];
            $dropdowns = $section['dropdowns'];
            $textareas = $section['textareas'];

            foreach($inputs as $input){
                $this->document->setValue($input['id'], $input['value']);
            }

            foreach($addresses as $address){
                $this->document->setValue($address['id'], $address['value']);
            }

            foreach($dropdowns as $dropdown){
                $this->document->setValue($dropdown['id'], $dropdown['value']);
            }

            foreach($textareas as $textarea){
                $this->document->setValue($textarea['id'], $textarea['value']);
            }
        }*/
        // TODO Valiation Respect

        // Generate PDF
        $pdfGenerator = new DocumentGenerator($decoded);
        $pdfGenerator->generateConvention();

        // Initialize Models
        $this->studentModel = new StudentModel();
        $this->companyModel = new CompanyModel();
        $this->uniceModel = new UniceModel();
        $this->employeeModel = new EmployeeModel();
        $this->internshipModel = new InternshipModel();
        // Insert all Datas
        foreach ($decoded as $section){
           $this->doActionFor($section);
        }
        // Save new datas
        $this->studentModel->save();
        $this->companyModel->save();
        $this->uniceModel->save();
        $this->employeeModel->company_id = $this->companyModel->id;
        $this->employeeModel->save();
        $this->internshipModel->save();

        return $this->ok($response, 'convention created');
    }

    /**
     * Dispatch actions
     */
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
            case 'Informations complémentaires' :
                $this->supplementsAction($section);
                break;
        }
    }

    /**
     * Register student
     * @param $section
     */
    private function studentAction($section){
        $inputs = $section['inputs'];
        $dropdowns = $section['dropdowns'];
        $addresses = $section['addresses'];
        foreach ($inputs as $input){
            switch ($input['id']){
                case 'student_name' :
                    $this->studentModel->name = $input['value'];
                    break;
                case 'student_surname' :
                    $this->studentModel->surname = $input['value'];
                    break;
                case 'student_ss' :
                    $this->studentModel->ss = $input['value'];
                    break;
                case 'student_unice_number' :
                    $this->studentModel->num = $input['value'];
                    break;
                case 'student_email' :
                    $this->studentModel->email = $input['value'];
                    break;
                case 'student_dob' :
                    $this->studentModel->dob = $input['value'];
                    break;
                case 'student_phone' :
                    $this->studentModel->phone = $input['value'];
                    break;
                case 'student_insurance' :
                    $this->studentModel->insurance = $input['value'];
                    break;
                case 'student_policy' :
                    $this->studentModel->police = $input['value'];
                    break;
            }
        }
        foreach ($dropdowns as $dropdown){
            switch ($dropdown['id']){
                case 'student_gender' :
                    $this->studentModel->gender = $dropdown['value'];
                    break;
                case 'promotion' :
                    $this->studentModel->promotion = $dropdown['value'];
                    break;
            }
        }
        foreach ($addresses as $address){
            if ($address['id'] == 'student_address'){
                $this->studentModel->address = $address['value'];
            }
        }
    }

    /**
     * Register company
     */
    private function companyAction($section){
        $inputs = $section['inputs'];
        $dropdowns = $section['dropdowns'];
        $addresses = $section['addresses'];
        foreach ($inputs as $input){
            switch ($input['id']){
                case 'ent_name' :
                    $this->companyModel->name = $input['value'];
                    break;
                case 'ent_website' :
                    $this->companyModel->website = $input['value'];
                    break;
                case 'ent_director_surname':
                    $this->companyModel->director_surname = $input['value'];
                    break;
                case 'ent_director_name' :
                    $this->companyModel->director_name = $input['value'];
                    break;
                case 'ent_director_email' :
                    $this->companyModel->director_email = $input['value'];
                    break;
                case 'ent_director_phone' :
                    $this->companyModel->director_phone = $input['value'];
                    break;
                case 'ent_director_quality' :
                    $this->companyModel->director_quality = $input['value'];
                    break;
            }
        }
        foreach ($dropdowns as$dropdown){
            if($dropdown['id'] == 'ent_director_gender'){
                $this->companyModel->director_gender = $dropdown['value'];
            }
        }
        foreach ($addresses as $address){
            switch ($address['id']){
                case 'ent_address' :
                    $this->companyModel->address = $address['value'];
                    break;
                case 'ent_stage_address' :
                    $this->internshipModel->address = $address['value'];
                    break;
            }
        }
    }

    /**
     * Register internship
     */
    private function internshipAction($section){
        $inputs = $section['inputs'];
        $dropdowns = $section['dropdowns'];
        $textareas = $section['textareas'];
        foreach ($inputs as $input){
            switch ($input['id']){
                case 'internship_dos' :
                    $this->internshipModel->start = strtotime($input['value']);
                    break;
                case 'internship_doe' :
                    $this->internshipModel->end = strtotime($input['value']);
                    break;
                case 'internship_week_hours' :
                    $this->internshipModel->working_hours = $input['value'];
                    break;
                case 'internship_remuneration' :
                    $this->internshipModel->income = $input['value'];
                    break;
                case 'internship_title' :
                    $this->internshipModel->subject = $input['value'];
                    break;
            }
        }
        foreach ($dropdowns as $dropdown){
            if($dropdown['id'] == 'internship_remuneration_way'){
                $this->internshipModel->payement = $dropdown['value'];
            }
        }
        foreach ($textareas as$textarea){
            switch ($textarea['id']){
                case 'internship_hours_text' :
                    $this->internshipModel->weekly_duration = $textarea['value'];
                    break;
                case 'internship_extras_text' :
                    $this->internshipModel->extra_work = $textarea['value'];
                    break;
                case 'internship_advantages' :
                    $this->internshipModel->advantages = $textarea['value'];
                    break;
                case 'internship_description' :
                    $this->internshipModel->detail = $textarea['value'];
                    break;
            }
        }
    }

    /**
     * Register responsables
     */
    private function responsablesAction($section){
        $inputs = $section['inputs'];
        $dropdowns = $section['dropdowns'];
        foreach ($inputs as $input) {
            switch($input['id']){
                case 'ent_tutor_surname' :
                    $this->employeeModel->surname = $input['value'];
                    break;
                case 'ent_tutor_name' :
                    $this->employeeModel->name = $input['value'];
                    break;
                case 'ent_tutor_email' :
                    $this->employeeModel->email = $input['value'];
                    break;
                case 'ent_tutor_phone' :
                    $this->employeeModel->phone = $input['value'];
                    break;
                case 'ent_tutor_quality' :
                    $this->employeeModel->quality = $input['value'];
                    break;
                case 'unice_tutor_surname' :
                    $this->uniceModel->surname = $input['value'];
                    break;
                case 'unice_tutor_name' :
                    $this->uniceModel->name = $input['value'];
                    break;
                case 'unice_tutor_email' :
                    $this->uniceModel->email = $input['value'];
                    break;
                case 'unice_tutor_phone' :
                    $this->uniceModel->phone = $input['value'];
                    break;
                case 'unice_tutor_quality' :
                    $this->uniceModel->quality = $input['value'];
                    break;
            }
        }
        foreach($dropdowns as $dropdown){
            switch($dropdown['id']){
              case 'ent_tutor_gender' :
                  $this->employeeModel->gender = $dropdown['value'];
                  break;
              case 'unice_tutor_gender' :
                  $this->uniceModel->gender = $dropdown['value'];
                  break;
            }
        }
    }

    /**
     * Register extra data
     */
    private function supplementsAction($section){
      $textareas = $section['textareas'];
      foreach ($textareas as $textarea){
          if($textarea['id'] == 'convention_extras'){
              $this->internshipModel->notes = $textarea['value'];
          }
      }
    }
}
