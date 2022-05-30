<?php 
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\UserDriver;
use App\Models\VehicleType;
use App\Models\Addcard;
use App\User;
use DB;
use Illuminate\Support\Facades\Storage;
 
class DriverRepository   {

    public function __construct(UserDriver $userDriver , VehicleType $vehicle_type ,Addcard $addcard){
        $this->userDriver = $userDriver;
        $this->vehicle_type = $vehicle_type ;
          $this->addcard = $addcard ;
    }

    public function getDriverById($id){
        return $this->userDriver->where('id',$id)->first();
    }

    public function removeUser($id)
    {  
       
        return $this->userDriver->where('id', $id)->update(['status' => 2]);
        // return $this->userDriver->where('id',$id)->delete();
    }
   
    public function getDriverList(){
        return $this->userDriver->where('status','!=','2')->orderBy('id','desc')->get();
    }
    public function getDriverUser()
    {
         return $this->userDriver->get();
    }
    public function checkEmail($email)
    {
        return $this->userDriver->where('email',$email)->first() ;
    }

    public function checkPhone($phone)
    {
        return $this->userDriver->where('phone',$phone)->first() ;
    }

    public function store($request){

        $numbers = md5(rand(999, 9999)) ;
        $randomCode = mb_substr($numbers, 0, 5); 
       
        $requestData = ['name' => ucfirst($request->name), 'email'=>$request->email , 'password'=>md5($request->password) ,'show_password'=>$request->password , 'phone'=>$request->phone , 'fcmToken'=>$request->fcmToken, 'my_referral_code'=>$randomCode];
        return $this->userDriver->insert($requestData);
    }

    public function driverLogin($request)
    {
        return $this->userDriver->where('email',$request->email)->where('password',md5($request->password))->first() ;
    }
    public function insertCardDetail($request)
    {
         $requestData = ['driver_id'=>$request->driver_id,'first_name' => ucfirst($request->first_name), 'last_name'=>$request->last_name , 'card_number'=>$request->card_number ,'security_code'=>$request->security_code , 'expiry_month'=>$request->expiry_month , 'expiry_year'=>$request->expiry_year];
        return $this->addcard->insert($requestData);
    }
    public function updateFCM($id,$fcm)
    {
        return $this->userDriver->where('id', $id)->update(['fcmToken' => $fcm]);
    }    

    public function updateDriverProfile($request)
    {
         
        $id = $request->driver_id ;

        if($request->name !='' ) 
            { $req['name'] = $request->name ; }

        if($request->email !='') 
            { $req['email'] = $request->email ; }

        if($request->phone !='') 
            { $req['phone'] = $request->phone ; }

        if($request->date_of_birth !='') 
            { $req['date_of_birth'] = $request->date_of_birth ; }

        if($request->address !='') 
            { $req['address'] = $request->address ; }    

        if($request->referred_code !='') 
            { $req['referred_code'] = $request->referred_code ; }    
           
        return $this->userDriver->where('id',$id)->update($req) ;
        
    }

    public function updatePasssword($id,$password)
    {
        return $this->userDriver->where('id', $id)->update(array('password' => md5($password),'show_password'=>$password)) ;        
    } 

    public function blockDriver($id)
    {
        return $this->userDriver->where('id', $id)->update(array('status'=>'3')) ;        
    } 

}