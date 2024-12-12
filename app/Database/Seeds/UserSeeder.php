<?php

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder{
    public function run(){
          $data=[];
          for($count=0;$count<50;$count++){
            $data[]=$this->generate_data();
          }
          $obj = new UserModel();
          $obj->insertBatch($data);
    }

    function generate_data(){
        $faker = Factory::create();
        return [
          "name"=>$faker->name(),
          "email"=>$faker->email(),
          "password"=>$faker->password(),
          "mobile"=>$faker->phoneNumber()
        ];
    }
}

?>