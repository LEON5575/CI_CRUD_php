<?php

namespace App\Controllers;

use App\Models\Demo;
use App\Models\UserModel;


//require FCPATH.'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Home extends BaseController
{
        protected $user;
    public function __construct(){
        helper(['url']);
        $this->user = new UserModel();
    }

    public function index()
    {
        echo view('inc/header') ;
        $data['users']=$this->user->orderBy('id','ASC')->paginate(10,'group1');
        $data['pager']= $this->user->pager;
        echo view('home',$data) ;
        echo view('inc/footer');
    }
    // public function index() {
    //     if ($this->request->getMethod() === 'post') {
    //         return $this->filterUser ();
    //     }
    //     echo view('inc/header');
    //     $data['users'] = $this->user->orderBy('id', 'ASC')->paginate(10, 'group1');
    //     $data['pager'] = $this->user->pager;
    //     echo view('home', $data);
    //     echo view('inc/footer');
    // }

    //*download in csv file
    public function spreadsheet() {
        $users = $this->user->findAll(); 
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1','Password');
        $sheet->setCellValue('E1', 'Mobile');
        
        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A'.$row, $user['id']);
            $sheet->setCellValue('B'.$row, $user['name']);
            $sheet->setCellValue('C'.$row, $user['email']);
            $sheet->setCellValue('D'.$row, $user['password']);
            $sheet->setCellValue('E'.$row, $user['mobile']);
            $row++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="data.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadSheet);
        $writer->save('php://output');
        exit;
    }
    //*add employee
    public function saveUser () {
        $name = $this->request->getVar('name');
        $email = $this->request->getVar('email');
        $mobile = $this->request->getVar('mobile');
        if (empty($name) || empty($email) || empty($mobile)) {
            session()->setFlashdata("error", "All fields are required.");
            return redirect()->back();
        }
        $existingUser  = $this->user->where('email', $email)->orWhere('mobile', $mobile)->first();
        if ($existingUser ) {
            session()->setFlashdata("error", "User  with this email or mobile number already exists.");
            return redirect()->back();
        }
        $this->user->save([
            "name" => $name,
            "email" => $email,
            "mobile" => $mobile,
        ]);
        session()->setFlashdata("success", "Data inserted successfully");
        return redirect()->to(base_url());
    }
    //!edit user data
    public function getUser ($id) {
        if (!is_numeric($id)) {
            return json_encode(['error' => 'Invalid user ID']);
        }
        $data = $this->user->find($id);

        if (!$data) {
            return json_encode(['error' => 'User  not found']);
        }
        return json_encode($data);
    }
    //!updateUser
    public function updateUser(){
        $id=$this->request->getVar('updateId');
        $name= $this->request->getVar('name');
        $email= $this->request->getVar('email');
        $mobile= $this->request->getVar('mobile');


        $data['name']=$name;
        $data['email']=$email;
        $data['mobile']=$mobile;

        $this->user->update($id,$data);
        return redirect()->to(base_url("/"));

    }

            //?delete user
        public function deleteUser(){
            $id=$this->request->getVar('id');
            $this->user->delete($id);
            // return redirect()->to(base_url('/'));
             echo "1";
        exit;

        }
        //!deleteAll
        public function deleteAll(){
            $ids= $this->request->getVar('ids');

            for($count=0;$count<count($ids);$count++){
                $this->user->delete($ids[$count]);
            }
            echo "multiple data deleted";
        }
      //!import user
      public function upload()
      {
          $upload_file = $_FILES['upload_file']['name'];
          $extension = pathinfo($upload_file,PATHINFO_EXTENSION);
          if($extension=='csv'){
             $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
          }else if($extension=='xls'){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
          }else{
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
          }
          $spreadsheet = $reader->load($_FILES['upload_file']['tmp_name']);
          $sheetdata = $spreadsheet->getActiveSheet()->toArray(); 
          

          $userModal = new UserModel();
          foreach($sheetdata as $index =>$row){
            if($index===0){
               continue;
            }

            $data=[
               'name'=>$row[1],
               'email'=>$row[2],
               'password'=>$row[3],
               'mobile'=>$row[4],
            ];
            $userModal->insert($data);
          }
          session()->setFlashdata("success","Data imported Successfully");
          return redirect()->to(base_url());
      }

    //   public function filterUser () {
    //   $userModal = new UserModel();
    //   $email = $this->request->getPost('email');
    //   $mobile = $this->request->getPost('mobile');
    //   $query = $userModal->select('*');
    //   if($email){
    //     $query->like('email',$email);
    //   }
    //   if($mobile){
    //     $query->like('mobile',$mobile);
    //   }

    //   $user['ci_crud'] = $query->findAll();
    //   $user['users'] = $userModal->findAll();
    //   return view('home',$user);
    // public function filterUser () {
    //     $email = $this->request->getPost('searchTerm1'); 
    //     $mobile = $this->request->getPost('searchTerm2'); 
    //     $query = $this->user->select('*');
    //     if (!empty($email) && !empty($mobile)) {
    //         $query->like('email', $email);
    //         $query->like('mobile', $mobile);
    //     }
    //     $filteredUsers = $query->findAll();
    //     $result= $this->response->setJSON($filteredUsers);
    //     return $result;
    // }
    public function filterUser  () {
        $email = $this->request->getPost('filterType1'); 
        $mobile = $this->request->getPost('filterType2'); 
        $query = $this->user->select('*');
        if (!empty($email) && !empty($mobile)) {
            $query->like('email', $email);
            $query->like('mobile', $mobile);
        }
        $filteredUsers = $query->findAll();
        $result = '';
        foreach ($filteredUsers as $user) {
            $result .= '<tr>';
            $result .= '<td>'.'</td>';
            $result .= '<td>'.$user['id'].'</td>';
            $result .= '<td>'.$user['name'].'</td>';
            $result .= '<td>'.$user['email'].'</td>';
            $result .= '<td>'.$user['mobile'].'</td>';
            $result .= '<td>';
         //  $result .= '<a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>';
           // $result .= '<a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>';
            $result .= '</td>';
            $result .= '</tr>';
        }
        return $result;
    }
    
}
?>