<?php 

                    namespace App\Ims\Employee\Model; 

                    class Employee {

                    protected $table = "Employee";

                    protected $primaryKey = "id";

protected $fillable = ['name','surname','gender','email','phone','quality'];}