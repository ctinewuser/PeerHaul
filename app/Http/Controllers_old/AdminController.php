<?php
namespace App\Http\Controllers;
use Helper; // Important
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Customer;
use App\Repository\DriverRepository ;
use App\Repository\CustomerRepository ;
use App\Repository\ListingRepository ;
use App\Repository\VehicleRepository ;
use App\Models\UserDriver;
use App\Models\Admin;
use App\Models\ParcelSize;

use DB;
use Hash;
use Auth;
use File;
use Image;
use Mail;
use Lang;
use Session;
use DateTime;

class AdminController extends Controller
 {
    
    public function __construct(DriverRepository $user_driver,Admin $admin , CustomerRepository $user_customer ,  ListingRepository $job_detail ,ListingRepository $getjobotherdetails  
    ,ListingRepository $vehicle_type ,ListingRepository $review ,ListingRepository $terms , ListingRepository $bidjob , ListingRepository $popup, VehicleRepository $vehicle, ParcelSize $parcel ,ListingRepository $deadline)
    {
       $this->user_driver = $user_driver ;
       $this->admin = $admin ;
       $this->user_customer = $user_customer ;
       $this->job_detail = $job_detail  ;
       $this->getjobotherdetails = $getjobotherdetails  ;
       $this->vehicle_type = $vehicle_type;
       $this->review = $review;
       $this->vehicle = $vehicle;
       $this->terms = $terms;
       $this->popup = $popup;
       $this->parcel = $parcel;
        $this->deadline = $deadline;
        $this->bidjob = $bidjob;
    } 

    public function loginPage()
    {
        $login = Session::get('admin_info') ['name'];
        if (!empty($login))
        {
            return redirect('dashboard');
        }
        else
        {
            return view('login');
        }
    }

    public function login(Request $request)
    {
        $username = $request->input('email');
        $password = $request->input('password');

        $user_record = $this->admin->where('email', $username)->where('password',md5($password))->first();
         
        if (empty($user_record))
        {
            Session::flash('error_msg', 'These Credentials does not match with our records!!');
            return redirect()->back();
        }
         
        if (!empty($user_record))
        {
            $session = array(
                'id' => $user_record->id,
                'name' => $user_record->name,
                'username' => $user_record->email,                
                'role' => $user_record->role,                
            );

            $request->session()->put('admin_info', $session);
            $users = $this->user_driver->getDriverList() ;
            
            return view('dashboard',compact('users'));

        }
        else
        {
            Session::flash('error_msg', 'Email/Password is incorrect!');
            return redirect()->back();
            
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        session()->forget('admin_info');
        return redirect('/');
    }

    public function dashboard(){
        $users = $this->user_driver->getDriverList() ;
        return view('dashboard',compact('users'));
    }

    // User Related Functions

    public function getAllUsers(){

        $allUsers = $this->user_driver->getDriverList() ;
        return view('users',compact('allUsers')) ;

    }
    public function deadlineList()
    {
         $list = $this->deadline->getDeadLine() ;
      return view('deadlineList',compact('list')) ;
    }
    public function  parcelList()
    {
      $parcelist = $this->job_detail->getParcelList() ;
      return view('parcelList',compact('parcelist')) ;

    }
    public function getTermsCondition()
    {
     
         $allTerms = $this->terms->getTermsData() ;
      return view('terms-condition',compact('allTerms')) ;
    }
    public function getPopupContent()
    {

         $allpopup = $this->popup->getPopupData() ;

      return view('popup_content',compact('allpopup')) ;
    }

    public function updateCustomer(Request $request)
    {

      $updatecus = $this->user_customer->updateCustomerProfile($request) ;
      if($updatecus){
        return redirect()->back()
               ->with('success_msg', 'Data Updated Successfully!');
        } else {    
            return redirect()->back()
               ->with('error_msg', 'Data Not Updated!');
        }
    }

    public function updateTerms(Request $request)
    {
      $update = $this->terms->updateTerms($request) ;
      if($update){   
        return redirect()->back()
               ->with('success_msg', 'Data Updated Successfully!');
        } else {    
            return redirect()->back()
               ->with('error_msg', 'Data Not Updated!');
        }
    }
    public function updateContent(Request $request)
    {
        
      $update = $this->popup->updateContent($request) ;

      if($update){   
        return redirect()->back()
               ->with('success_msg', 'Data Updated Successfully!');
        } else {    
            return redirect()->back()
               ->with('error_msg', 'Data Not Updated!');
        }
    }
    public function getAllCustomer()
    {
      $allCustomer = $this->user_customer->getCustomerList() ;
      return view('customer',compact('allCustomer')) ;

    }
    public function getAllVechile()
    {
      $allVechicle = $this->vehicle_type->getAllVechileList() ;
      return view('vechicle-list',compact('allVechicle')) ;

    }
   public function getAlljobDetail()
   {  
      $jobDetail = $this->job_detail->getJobDetailList();
      return view('jobdetail',compact('jobDetail')) ;
    }
    public function getReviewList()
    {
      $allReview = $this->review->getReviewList() ;
      return view('review',compact('allReview')) ;
    }
    public function getVehicleinfo()
    {
      $allVehicleinfo = $this->vehicle->getVehicleinfoList() ;
      return view('vehicle',compact('allVehicleinfo')) ;
    }
    public function getjobBidList()
    {
       $jobBid = $this->bidjob->getJobBidDetail() ;
      return view('bidjob',compact('jobBid')) ;
    }
    public function removeUser($id){
      
      $delete = $this->user_driver->removeUser(decrypt($id)) ;
 
       if($delete){
         
         return redirect()->back()
                ->with('success_msg', 'User Deleted Succesfully!');
         } else {
            
             return redirect()->back()
                ->with('error_msg', 'User Not Deleted!');
         }
    }
    public function removeCustomer($id){
      
      $delete = $this->user_customer->removeCustomer(decrypt($id)) ;
 
       if($delete){
         
         return redirect()->back()
                ->with('success_msg', 'Customer Deleted Succesfully!');
         } else {
            
             return redirect()->back()
                ->with('error_msg', 'Customer Not Deleted!');
         }
    }
    //============ User

    public function getAllNews(){

      $allNews = $this->uploadNews->orderBy('id', 'DESC')->get() ;
      return view('allNews',compact('allNews'));
    }

    public function unapprovedPost(){

      $allNews = $this->uploadNews->where('status','1')->orderBy('id', 'DESC')->get() ;
      return view('unapprovedPost',compact('allNews'));
    }


    public function changeUserSt(Request $request){

     if(!empty($_POST)){

        $userID = $request['userID'] ;
        $status = $request['status'] ;
        $changeFor = $request['changeFor'] ;

      if($changeFor == 'user') {
          $update = $this->user_driver->where('id',$userID)->update(['status'=>$status]);
          if($status == 1) { $myStatus = 'Deactivate'; } else { $myStatus = 'Activate'; }
      } else if($changeFor == 'news') {
          $update = $this->uploadNews->where('id',$userID)->update(['status'=>$status]);
          if($status == 1) { $myStatus = 'Disapproved'; } else { $myStatus = 'Approved'; }
      } else if($changeFor == 'admin') {
      	  $update = $this->admin->where('id',$userID)->update(['status'=>$status]);
          if($status == 1) { $myStatus = 'Deactivate'; } else { $myStatus = 'Activate'; }
      }
        

       if($update){
            $json_auth=array('success'=>1,'msg'=>$myStatus.' Succesfully' );
            echo json_encode($json_auth);
            exit();
        } else {
            $json_auth=array('success'=>0,'msg'=>'Fail to Update Status' );
            echo json_encode($json_auth);
            exit();
        }

     }
    }
   
   public function getFollowList(Request $request){
     if(!empty($_POST)){
        $userId = $request['userID'] ;
        $listType = $request['forFollow'] ;
 
         if($listType == 0){

        $followingList = DB::table('tbl_followusers')
            ->join('tbl_users', 'tbl_followusers.followed_user_id', '=', 'tbl_users.id')
            ->select('tbl_users.username', 'tbl_followusers.user_id', 'tbl_followusers.followed_user_id','tbl_users.profile_img')
            ->where('tbl_followusers.user_id',$userId)
            ->get();
             $type = "Following List ";

        } else {

          $followingList = DB::table('tbl_followusers')
            ->join('tbl_users', 'tbl_followusers.user_id', '=', 'tbl_users.id')
            ->select('tbl_users.username', 'tbl_followusers.user_id', 'tbl_followusers.followed_user_id', 'tbl_users.profile_img')
            ->where('tbl_followusers.followed_user_id',$userId)
            ->get();
             $type = "Follower List";
          }  

        $html = '' ; $i = 0 ;
      
         if(sizeof($followingList)>0) { 
           foreach($followingList as $followList) { $i++;

             $html .= '<tr> <td>'.$i.'.</td> <td>'.$followList->username.'</td> </tr>' ;

           }
         }   

        if($html){
            $json_auth=array('success'=>1,'msg'=>$html, 'type'=>$type );
            echo json_encode($json_auth);
            exit();
        } else {
            $json_auth=array('success'=>0,'msg'=>'No List Found' );
            echo json_encode($json_auth);
            exit();
        }

      }
    }

    public function getAllSubUsers(){
        $allUsers = DB::table('tbl_admin')->where('role','!=','0')->orderBy('id','DESC')->get() ;
        return view('sub-users',compact('allUsers'));
    }
   
    public function addSubAdmin(Request $request){

      $post = $request->all();
           
        if(!empty($post)) {
           
          // check Email & Phone

           $email = $post['email'] ;
 
           $checkEmail = $this->admin->where('email',$post['email'])->first();
           if($checkEmail) { Session::flash('error_msg', 'Email Already Exist !'); return redirect()->back() ; }
 
           $checkUsername = $this->admin->where('username',$post['username'])->first();
           if($checkUsername) { Session::flash('error_msg', 'Username Already Exist !'); return redirect()->back() ; }
 
           $checkContact = $this->admin->where('contact',$post['contact'])->first();
           if($checkContact) { Session::flash('error_msg', 'Contact Already Exist !'); return redirect()->back() ; }

           $userCreate = $this->admin->insert([
           	'username'=>$post['username'],
           	'email'=>$post['email'],
           	'contact'=>$post['contact'],
           	'password'=>md5($post['password']),
           	'name'=>$post['name'],
           	'role'=>$post['role']] );

             if(!empty($userCreate)) {
                 Session::flash('success_msg', 'User Added Succesfully!');
                 return redirect()->back();
              } else {
                 DB::rollback() ;
                 Session::flash('error_msg', 'User Not Created !');
                 return redirect()->back() ;
            }
          } else {
                DB::rollback() ;
                Session::flash('error_msg', 'Parameter Missing !');
                return redirect()->back() ;
        }
    
    }
   //////////////Helper function call////////////
   public function username()
   {
     return Helper::getUserDetails();
   }
    ///////////////////////////////////////////////////
    public function removeAdmin($id){

      $delete = $this->admin->where('id',decrypt($id))->delete() ;
 
       if($delete){
            return redirect()->back()->with('success_msg', 'User Deleted Succesfully!');
         } else {
            return redirect()->back()->with('error_msg', 'User Not Deleted!');
         }
    }

    public function removePost($id){

      $delete = $this->uploadNews->where('id',decrypt($id))->delete() ;
 
       if($delete){
            return redirect()->back()->with('success_msg', 'User Deleted Succesfully!');
         } else {
            return redirect()->back()->with('error_msg', 'User Not Deleted!');
         }
    }

    public function viewSinglePost($id){

      $getDetails = $this->uploadNews->where('id',decrypt($id))->first() ;
      $getLikedUser = DB::table('tbl_newsLike')
            ->join('tbl_users', 'tbl_newsLike.user_id', '=', 'tbl_users.id')
            ->select('tbl_users.username', 'tbl_newsLike.user_id' )
            ->where('tbl_newsLike.news_id',decrypt($id))
            ->get() ;
 
      $getShare = $this->share->select('id')->where('news_id',decrypt($id))->count() ;
      $getComment = $this->newsComment->where('news_id',decrypt($id))->get() ;

       if($getDetails){
            return view('view-post-details',compact('getDetails','getLikedUser','getShare','getComment'));
         } else {
            return redirect()->back()->with('error_msg', 'No Details Found !');
       }
    }
    ///Added by nain  14/12/21
    public function getUserById($id)
    {
      $getDetails = $this->user_driver->getDriverById($id) ;
  
      return view('view-details',compact('getDetails')) ;

    }
    public function getCustomerById($id)
    {
      $getDetails = $this->user_customer->getCustomerById($id) ;
  
      return view('view-customer',compact('getDetails')) ;

    }
    
    public function editContentById($id)
   {
    //$getjobDetails = $this->job_detail->viewJobDetails($id) ;
    $editContents = $this->popup->changeDataById($id);
    
    return view('view-job',compact('getjobDetails')) ;
   }


   public function getJobDetailById($id)
   {
    //$getjobDetails = $this->job_detail->viewJobDetails($id) ;
      $getList = $this->parcel->first();
      $getjobDetails = $this->job_detail->viewJobListDetails($id);
       
      return view('view-job',compact('getjobDetails','getList')) ;
   }
}