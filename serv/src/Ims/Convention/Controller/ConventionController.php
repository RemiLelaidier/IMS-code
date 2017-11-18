<?php

namespace App\Ims\Convention\Controller;

use App\Core\Controller\Controller;
use App\Core\Generator\PDF\PDFGenerator;
use App\Core\Validator\Validator;
use App\Ims\Convention\Model\ConventionModel;
use App\Ims\Student\Model\StudentModel;
use App\Ims\Company\Model\CompanyModel;
use App\Ims\Internship\Model\InternshipModel;
use App\Ims\Unice\Model\UniceModel;
use App\Ims\Employee\Model\EmployeeModel;
use Cocur\Slugify\Slugify;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Core\Generator\DocumentGenerator;

class ConventionController extends Controller
{
    /**
     * @var StudentModel
     */
    private $studentModel;

    /**
     * @var CompanyModel
     */
    private $companyModel;

    /**
     * @var UniceModel
     */
    private $uniceModel;

    /**
     * @var EmployeeModel
     */
    private $employeeModel;

    /**
     * @var InternshipModel
     */
    private $internshipModel;

    /**
     * @var array model
     */
    private $model;

    /**
     * Method submit
     * Put all the datas received from the four stages of filling the convention in the database
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Exception
     */
    public function submit(Request $request, Response $response){
        //opcache_reset();
        
        // Decode datas
        $conventionData = $request->getBody()->getContents();

        $decoded = json_decode($conventionData, true);
        $this->model = $decoded;

        // Log activity
        $this->logger->debug('New convention on ' . get_class($this) . ":submit", [
            'data' => $conventionData
        ]);

        $validator = new Validator($this->getValidationRules());
        $validator->validateParams($decoded);
        $errors = $validator->getErrors();
        if(!empty($errors)){
            return $this->json($response, $errors, 400);
        }

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

        $this->generateConventionFor($this->studentModel->name . $this->studentModel->surname, $this->model);

        // Save new datas
        $this->studentModel->save();
        $this->companyModel->save();
        $this->uniceModel->save();

        // Setting relationships
        $this->employeeModel->company_id = $this->companyModel->id;

        $this->employeeModel->save();
        $this->internshipModel->save();

        return $this->ok($response, 'convention created');
    }

    /**
     * Dispatch actions
     *
     * @param $section
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
     *
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
     *
     * @param $section
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
     *
     * @param $section
     */
    private function internshipAction($section){
        $inputs = $section['inputs'];
        $dropdowns = $section['dropdowns'];
        $textareas = $section['textareas'];
        foreach ($inputs as $input){

            if(!array_key_exists('value', $input))
                continue;

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

            if(!array_key_exists('value', $dropdown))
                continue;

            if($dropdown['id'] == 'internship_remuneration_way'){
                $this->internshipModel->payement = $dropdown['value'];
            }
        }
        foreach ($textareas as $textarea){

            if(!array_key_exists('value', $textarea))
                continue;

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
     *
     * @param $section
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
     *
     * @param $section
     */
    private function supplementsAction($section){
      $textareas = $section['textareas'];
      foreach ($textareas as $textarea){
          if($textarea['id'] == 'convention_extras'){
              $this->internshipModel->notes = $textarea['value'];
          }
      }
    }

    /**
     * Calculated fields to add in template
     *
     * @return array fields for Convention
     */
    private function calculatedForConvention(): array {
        $currentYear = date('Y');
        $nextYear = $currentYear+1;
        $finalSchoolYear = $currentYear . "-" . $nextYear;

        return [
            'school_year' => $finalSchoolYear,

            // TODO XXX : Add in UI
            'student_usage_name'          => "",
            "internship_service"          => "",
            "internship_hours"            => "",
            "internship_hours_daysOrWeek" => "",

            // TODO XXX : Calc
            "internship_duration"         => "",
            "internship_daysOrMonth"      => "",
            "internship_presence_days"    => ""
        ];
    }

    /**
     * Generate convention and save in assets/Year-PeopleFullName.docx
     *
     * @param string $name
     * @param array  $model
     *
     * @throws \Exception
     */
    private function generateConventionFor(string $name, array $model){
        $extras = $this->calculatedForConvention();

        //$wordGenerator = new DocumentGenerator($model, "convention/convention_template", date('Y') . "-" . $name, $extras);
        //$wordGenerator->writeAndSave('convention/generated');

        // @Tool : Toggle to preview pdf generation
        $slugify = new Slugify();
        $pdfName = $slugify->slugify($name);

        $original = $this->findBase() . "/assets/convention/convention_compatibility.pdf";
        $merged = $this->findBase() . "/assets/convention/generated/$pdfName.pdf";

        $pdfGenerator = new PDFGenerator($this->getMappedFields(), $model);
        $pdfGenerator->start($original, $merged);
    }

    private function getMappedFields(){
        $fields = $this->findBase() . "/assets/convention/fields.json";

        return json_decode(file_get_contents($fields), true);
    }

    /**
     * Get every convention data based on our sample
     * TODO : Change to received data
     *
     * @return array
     */
    private function getConventionData(){
        $data = $this->findBase() . "/assets/convention/sample.json";

        return json_decode(file_get_contents($data), true);
    }

    /**
     * Get every validation rule for this Entity (Convention)
     *
     * @return array
     */
    private function getValidationRules(){
        return [
            [
                "key" => "student_surname",
                "type" => "string"
            ],
            [
                "key" => "student_name",
                "type" => "string"
            ],
            [
                "key" => "student_ss",
                "type" => "string"
            ],
            [
                "key" => "student_unice_number",
                "type" => "intVal"
            ],
            [
                "key" => "student_email",
                "type" => "email"
            ],
            [
                "key" => "student_phone",
                "type" => "phone"
            ],
            [
                "key" => "student_insurance",
                "type" => "string"
            ],
            [
                "key" => "student_policy",
                "type" => "intVal"
            ],
            [
                "key" => "promotion",
                "type" => "string"
            ],
            [
                "key" => "student_gender",
                "type" => "string"
            ],
            [
                "key" => "student_address",
                "type" => "string"
            ],
             [
                "key" => "ent_name",
                "type" => "string"
            ],
            [
                "key" => "ent_website",
                "type" => "url"
            ],
            [
                "key" => "ent_director_surname",
                "type" => "string"
            ],
            [
                "key" => "ent_director_name",
                "type" => "string"
            ],
            [
                "key" => "ent_director_email",
                "type" => "email"
            ],
            [
                "key" => "ent_director_phone",
                "type" => "phone"
            ],
            [
                "key" => "ent_director_quality",
                "type" => "string"
            ],
            [
                "key" => "ent_director_gender",
                "type" => "string"
            ],
            [
                "key" => "ent_address",
                "type" => "string"
            ],
            [
                "key" => "ent_stage_address",
                "type" => "string"
            ],
            [
                "key" => "internship_dos",
                "type" => "date"
            ],
            [
                "key" => "internship_doe",
                "type" => "date"
            ],
            [
                "key" => "internship_week_hours",
                "type" => "intVal"
            ],
            [
                "key" => "internship_remuneration",
                "type" => "intVal"
            ],
            [
                "key" => "internship_title",
                "type" => "string"
            ],
            [
                "key" => "internship_hours_text",
                "type" => "string"
            ],
            [
                "key" => "internship_extra_text",
                "type" => "string"
            ],
            [
                "key" => "internship_advantages",
                "type" => "string"
            ],
            [
                "key" => "internship_description",
                "type" => "string"
            ],
            [
                "key" => "internship_remuneration_way",
                "type" => "string"
            ],
            [
                "key" => "ent_tutor_name",
                "type" => "string"
            ],
            [
                "key" => "ent_tutor_surname",
                "type" => "string"
            ],
            [
                "key" => "ent_tutor_email",
                "type" => "email"
            ],
            [
                "key" => "ent_tutor_phone",
                "type" => "phone"
            ],
            [
                "key" => "ent_tutor_quality",
                "type" => "string"
            ],
            [
                "key" => "unice_tutor_name",
                "type" => "string"
            ],
            [
                "key" => "unice_tutor_surname",
                "type" => "string"
            ],
            [
                "key" => "unice_tutor_email",
                "type" => "email"
            ],
            [
                "key" => "unice_tutor_phone",
                "type" => "phone"
            ],
            [
                "key" => "unice_tutor_quality",
                "type" => "string"
            ],
            [
                "key" => "ent_tutor_gender",
                "type" => "string"
            ],
            [
                "key" => "unice_tutor_gender",
                "type" => "string"
            ],
            [
                "key" => "convention_extras",
                "type" => "string"
            ],
        ];
    }

    public function findBase(){
        $directory = __FILE__;
        $root = null;

        // If not found and dir not root..root?
        while(is_null($root) && $directory != '/'){
            $directory = dirname($directory);
            $composerConfig = $directory . '/.watchi';

            if(file_exists($composerConfig))
                $root = $directory;

        }

        return $root;
    }
}
