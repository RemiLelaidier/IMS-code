<?php

namespace App\Ims\Convention\Controller;

use Illuminate\Database\Eloquent\Model;
use App\Core\Controller\Controller;
use App\Core\Generator\PDF\Field;
use App\Core\Generator\PDF\PDFGenerator;
use App\Core\Generator\Word\TemplateValue;
use App\Core\Validator\Validator;
use App\Ims\Student\Model\StudentModel;
use App\Ims\Company\Model\CompanyModel;
use App\Ims\Internship\Model\InternshipModel;
use App\Ims\Unice\Model\UniceModel;
use App\Ims\Employee\Model\EmployeeModel;
use Cocur\Slugify\Slugify;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Core\Generator\Word\DocumentGenerator;

/**
 * Class ConventionController
 * @package App\Ims\Convention\Controller
 *
 * TODO : Need to regen fields.json with new form builded using Adobe Acrobat
 *
 */
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
        opcache_reset();
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

        $this->generateConventionFor($this->studentModel->name . $this->studentModel->surname);

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
                    $this->saveValue($this->studentModel, 'name', $input);
                    break;
                case 'student_surname' :
                    $this->saveValue($this->studentModel, 'surname', $input);
                    break;
                case 'student_ss' :
                    $this->saveValue($this->studentModel, 'ss', $input);
                    break;
                case 'student_unice_number' :
                    $this->saveValue($this->studentModel, 'num', $input);
                    break;
                case 'student_email' :
                    $this->saveValue($this->studentModel, 'email', $input);
                    break;
                case 'student_dob' :
                    $this->saveValue($this->studentModel, 'dob', $input);
                    break;
                case 'student_phone' :
                    $this->saveValue($this->studentModel, 'phone', $input);
                    break;
                case 'student_insurance' :
                    $this->saveValue($this->studentModel, 'insurance', $input);
                    break;
                case 'student_policy' :
                    $this->saveValue($this->studentModel, 'police', $input);
                    break;
            }
        }
        foreach ($dropdowns as $dropdown){
            switch ($dropdown['id']){
                case 'student_gender' :
                    $this->saveValue($this->studentModel, 'gender', $dropdown);
                    break;
                case 'promotion' :
                    $this->saveValue($this->studentModel, 'promotion', $dropdown);
                    break;
            }
        }
        foreach ($addresses as $address){
            if ($address['id'] == 'student_address'){
                $this->saveValue($this->studentModel, 'address', $address);
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
                    $this->saveValue($this->companyModel, 'name', $input);
                    break;
                case 'ent_website' :
                    $this->saveValue($this->companyModel, 'website', $input);
                    break;
                case 'ent_director_surname':
                    $this->saveValue($this->companyModel, 'director_surname', $input);
                    break;
                case 'ent_director_name' :
                    $this->saveValue($this->companyModel, 'director_name', $input);
                    break;
                case 'ent_director_email' :
                    $this->saveValue($this->companyModel, 'director_email', $input);
                    break;
                case 'ent_director_phone' :
                    $this->saveValue($this->companyModel, 'director_phone', $input);
                    break;
                case 'ent_director_quality' :
                    $this->saveValue($this->companyModel, 'director_quality', $input);
                    break;
            }
        }
        foreach ($dropdowns as$dropdown){
            if($dropdown['id'] == 'ent_director_gender'){
                $this->saveValue($this->companyModel, 'director_gender', $dropdown);
            }
        }
        foreach ($addresses as $address){
            switch ($address['id']){
                case 'ent_address' :
                    $this->saveValue($this->companyModel, 'address', $address);
                    break;
                case 'ent_stage_address' :
                    $this->saveValue($this->internshipModel, 'address', $address);
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
                    $this->internshipModel->start = strtotime($this->convertToAmericanDate($input['value']));
                    break;
                case 'internship_doe' :
                    $this->internshipModel->end = strtotime($this->convertToAmericanDate($input['value']));
                    break;
               case 'internship_week_hours' :
                   $this->saveValue($this->internshipModel, 'working_hours', $input);
                    break;
                case 'internship_remuneration' :
                    $this->saveValue($this->internshipModel, 'income', $input);
                    break;
                case 'internship_title' :
                    $this->saveValue($this->internshipModel, 'subject', $input);
                    break;
            }
        }
        foreach ($dropdowns as $dropdown){

            if(!array_key_exists('value', $dropdown))
                continue;

            if($dropdown['id'] == 'internship_remuneration_way'){
                $this->saveValue($this->internshipModel, 'payement', $dropdown);
            }
        }
        foreach ($textareas as $textarea){

            if(!array_key_exists('value', $textarea))
                continue;

            switch ($textarea['id']){
                case 'internship_hours_text' :
                    $this->saveValue($this->internshipModel, 'weekly_duration', $textarea);
                    break;
                case 'internship_extras_text' :
                    $this->saveValue($this->internshipModel, 'extra_work', $textarea);
                    break;
                case 'internship_advantages' :
                    $this->saveValue($this->internshipModel, 'advantages', $textarea);
                    break;
                case 'internship_description' :
                    $this->saveValue($this->internshipModel, 'detail', $textarea);
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
                    $this->saveValue($this->employeeModel, 'surname', $input);
                    break;
                case 'ent_tutor_name' :
                    $this->saveValue($this->employeeModel, 'name', $input);
                    break;
                case 'ent_tutor_email' :
                    $this->saveValue($this->employeeModel, 'email', $input);
                    break;
                case 'ent_tutor_phone' :
                    $this->saveValue($this->employeeModel, 'phone', $input);
                    break;
                case 'ent_tutor_quality' :
                    $this->saveValue($this->employeeModel, 'quality', $input);
                    break;
                case 'unice_tutor_surname' :
                    $this->saveValue($this->uniceModel, 'surname', $input);
                    break;
                case 'unice_tutor_name' :
                    $this->saveValue($this->uniceModel, 'name', $input);
                    break;
                case 'unice_tutor_email' :
                    $this->saveValue($this->uniceModel, 'email', $input);
                    break;
                case 'unice_tutor_phone' :
                    $this->saveValue($this->uniceModel, 'phone', $input);
                    break;
                case 'unice_tutor_quality' :
                    $this->saveValue($this->uniceModel, 'quality', $input);
                    break;
            }
        }
        foreach($dropdowns as $dropdown){
            switch($dropdown['id']){
              case 'ent_tutor_gender' :
                  $this->saveValue($this->employeeModel, 'gender', $dropdown);
                  break;
              case 'unice_tutor_gender' :
                  $this->saveValue($this->uniceModel, 'gender', $dropdown);
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
              $this->saveValue($this->internshipModel, 'notes', $textarea);
          }
      }
    }

    /**
     * Generate convention and save in assets/Year-PeopleFullName.docx
     *
     * @param string $name
     *
     * @throws \Exception
     */
    private function generateConventionFor(string $name) {
        /*$wordGenerator = new DocumentGenerator($this->getModelAsTemplateValues(), "convention/convention_template", date('Y') . "-" . $name);
        $wordGenerator->writeAndSave($this->getAssetsFolder() . "convention/generated/");*/

        $slugify = new Slugify();
        $pdfName = $slugify->slugify($name);

        $original = $this->getAssetsFolder() . "convention/convention_compatibility.pdf";
        $merged = $this->getAssetsFolder() . "convention/generated/$pdfName.pdf";

        $pdfGenerator = new PDFGenerator($this->getMappedFields(), $this->getConventionModel(), 'P', 'pt', 'A4');
        $pdfGenerator->start($original, $merged);
    }

    /**
     *
     * @return array
     */
    public function getModelAsTemplateValues() : array {
        $templateValues = [];
        $model = $this->getConventionModel();

        foreach($model as $k => $value){
            $templateValues[] = new TemplateValue($k, $value);
        }

        return $templateValues;
    }

    /**
     * @return array
     */
    private function getConventionModel() : array {
        $currentYear = date('Y');
        $nextYear = $currentYear+1;
        $finalSchoolYear = $currentYear . "-" . $nextYear;

        $dob = explode('/', $this->studentModel->dob);

        $dateStart = new \DateTime();
        $dateStart->setTimestamp($this->internshipModel->start);

        $dateEnd = new \DateTime();
        $dateEnd->setTimestamp($this->internshipModel->end);

        $diff = $dateEnd->diff($dateStart);

        // TODO : Every field with blank value should be completed

        return [
            'student_dob_day'             => $dob[0],
            'student_dob_month'           => $dob[1],
            'student_dob_year'            => $dob[2],
            'student_dob'                 => $this->studentModel->dob,
            'student_email'               => $this->studentModel->email,
            'student_gender'              => ($this->studentModel->gender == "M") ? "Masculin" : "Féminin",
            'student_gender_female'       => ($this->studentModel->gender == "M") ? "" : "X",
            'student_gender_male'         => ($this->studentModel->gender == "M") ? "X" : "",
            'internship_title'            => $this->internshipModel->subject,
            'internship_dos'              => date('d/m/Y', $this->internshipModel->start),
            'internship_doe'              => date('d/m/Y', $this->internshipModel->end),
            'internship_duration'         => $diff->format('%m'),
            'school_year'                 => $finalSchoolYear,

            'internship_type_opt_1'       => "X",
            'internship_type_opt_2'       => "",
            'internship_type_opt_3'       => "",

            'ent_name'                    => $this->companyModel->name,
            'ent_address_2'               => $this->companyModel->address,
            'ent_director_fullname'       => $this->companyModel->director_surname . " " . $this->companyModel->director_name,
            'ent_director_quality'        => $this->companyModel->director_quality,
            'ent_director_phone'          => $this->companyModel->director_phone,
            'ent_director_email'          => $this->companyModel->director_email,
            'ent_stage_address'           => $this->internshipModel->address,

            'student_name'                => $this->studentModel->name,
            'student_surname'             => $this->studentModel->surname,
            'student_address'             => $this->studentModel->address,
            'student_phone'               => $this->studentModel->phone,
            'student_unice_number'        => $this->studentModel->num,
            'student_formation'           => $this->studentModel->promotion . " MIAGE",
            'internship_detail'           => $this->internshipModel->detail,

            'unice_tutor_fullname'        => $this->uniceModel->surname . " " . $this->uniceModel->name,
            //'ent_tutor_fullname'          => $this->employeeModel->surname . " " . $this->employeeModel->name,
            'unice_tutor_quality'         => $this->uniceModel->quality,
            //'ent_tutor_quality'           => $this->employeeModel->quality,
            'unice_tutor_phone'           => $this->uniceModel->phone,
            //'ent_tutor_phone'             => $this->employeeModel->phone,
            'unice_tutor_email'           => $this->uniceModel->email,
            //'ent_tutor_email'             => $this->employeeModel->email,
            'student_insurance'           => $this->studentModel->insurance,

            'activity_1'                  => "",
            'activity_2'                  => "",
            'activity_3'                  => "",
            'activity_4'                  => "",
            'activity_5'                  => "",
            'activity_6'                  => "",

            'competence_1'                => "",
            'competence_2'                => "",
            'competence_3'                => "",
            'competence_4'                => "",
            'competence_5'                => "",
            'competence_6'                => "",

            'extra_1'                     => "",
            'extra_2'                     => "",
            'extra_3'                     => "",
            'extra_4'                     => "",

            'internship_remuneration'     => $this->internshipModel->income,
            'internship_vacation'         => "",
            'attest_ent_name'             => $this->companyModel->name,
            'attest_ent_address'          => $this->companyModel->address,
            'attest_student_name'         => $this->studentModel->name,

            // TODO : Field is not in fields.json
            'attest_student_surname'      => $this->studentModel->surname,

            'attest_student_usage_name'   => "",

            'attest_student_dob_day'      => $dob[0],
            'attest_student_dob_month'    => $dob[1],
            'attest_student_dob_year'     => $dob[2],
            'attest_student_address'      => $this->studentModel->address,
            'attest_student_email'        => $this->studentModel->email,
            'attest_student_formation'    => $this->studentModel->promotion . " MIAGE",
            'attest_student_university'   => "Université Nice Sophia-Antipolis",

            'student_usage_name'          => "",
            "internship_service"          => "",
            "internship_hours"            => $this->internshipModel->working_hours,
            "internship_hours_daysOrWeek" => ($this->internshipModel->working_hours < 24) ? "jours" : "mois",

            "internship_daysOrMonth"      => ($diff->days > 30) ? "mois" : "jours",
            "internship_presence_days"    => ""
        ];
    }

    /**
     * Get mappings for convention
     *
     * @return array
     * @throws \Exception : invalid field
     */
    private function getMappedFields() : array {
        $fields = $this->getAssetsFolder() . "convention/fields.json";

        $fields = json_decode(file_get_contents($fields), true);

        $fieldEntities = [];

        foreach($fields as $field) {
            $fieldEntities[] = Field::fieldFromArray($field);
        }

        return $fieldEntities;
    }

    /**
     * Get every validation rule for this Entity (Convention)
     *
     * @return array
     */
    private function getValidationRules() : array {
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

    /**
     * @return null|string
     */
    public function getAssetsFolder() : string {
        return dirname(dirname(getcwd())) . "/assets/";
    }

    public function convertToAmericanDate($dateFR) : string {
        $date = explode("/", $dateFR);

        return $date[2].'-'.$date[1].'-'.$date[0];
    }

    /**
     * @param Model  $model
     * @param string $key
     * @param array  $data
     */
    private function saveValue (Model $model, string $key, array $data): void {
        $value = "";
        if (array_key_exists('value', $data)) {
            $value = $data['value'];
        }

        $model->$key = $value;
    }
}
