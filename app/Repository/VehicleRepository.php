<?php 
namespace App\Repository ;
use Illuminate\Database\Eloquent\Model ;
use Carbon\Carbon ;
use Illuminate\Support\Facades\Log ;
use App\Models\UserDriver ;
use App\Models\Vehicle ;
use App\Models\VehicleType ;
use App\User ;
use DB ;
use Illuminate\Support\Facades\Storage ;
 
class VehicleRepository {

    public function __construct(UserDriver $userDriver , Vehicle $vehicle , VehicleType $vehicleType){
        $this->userDriver = $userDriver ;
        $this->vehicle = $vehicle ;
        $this->vehicleType = $vehicleType ;
    }
    
    public function vehicle_type()
    {
        return $this->vehicleType->select('id','vehicle_name')->get() ;
    }

    public function getVehicleinfoList()
    {

 return DB::table('tbl_vehicle_info')
            ->select('tbl_vehicle_info.*', 'tbl_driver_users.name')
           
            ->leftJoin('tbl_driver_users', 'tbl_driver_users.id', '=', 'tbl_vehicle_info.driver_id')
            ->get();
    }
    public function uploadImage($request){
        try {
            $data = [];
            if($request->hasfile('image'))
            {
                foreach($request->file('image') as $key=>$file)
                {
                    $uploadImg = time().rand(111111,999999).'.'.$file->getClientOriginalExtension();
                    $destinationPath = storage_path('app/temp');
                    $img = Image::make($file->getRealPath());
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$uploadImg);
                    $file->move($destinationPath, $uploadImg);
                    $data = url('storage/app').'/temp/'.$uploadImg;
                
                }
            }

             if(!empty($data)){
                $image =  explode(",",$data);
                return response()->json(['success'=>true,'message' => '','data'=> ['image'=>$image]], 200);
             }
        
          } catch (\Exception $e) {
             return response()->json(['success'=>false,'message' => $e->getMessage(),'data'=> []], 200);
        }
    }

    public function storeVehicleInfo($request)
     {
        //  upload_vehicle_image
        if ($request['upload_vehicle_image']) {

            if ($request->hasFile('upload_vehicle_image'))
            {
                $image = $request->file('upload_vehicle_image');
                $image_1 = time().rand(111111,999999).'.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/vehicle/');
                $image->move($destinationPath, $image_1);
            }
          } else { $image_1 = '' ; }  

        //  driver_license_front
        if ($request['driver_license_front']) {

            if ($request->hasFile('driver_license_front'))
            {
                $image = $request->file('driver_license_front');
                $image_2 = time().rand(111111,999999). '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/vehicle/');
                $image->move($destinationPath, $image_2);
            }
          } else { $image_2 = '' ; }  

          //  driver_license_front
        if ($request['driver_license_back']) {

            if ($request->hasFile('driver_license_back'))
            {
                $image = $request->file('driver_license_back');
                $image_3 = time().rand(111111,999999).'.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/vehicle/');
                $image->move($destinationPath, $image_3);
            }
          } else { $image_3 = '' ; }
       
        $requestData = [
            'driver_id' => $request->driver_id , 
            'upload_vehicle_image' => $image_1 ,
            'vechicle_make' => $request->vechicle_make , 
            'vechicle_model' => $request->vechicle_model , 
            'vechicle_license_plate' => $request->vechicle_license_plate ,  
            'vechicle_color' => $request->vechicle_color , 
            'vechicle_type' => $request->vechicle_type , 
            'driver_license_front' => $image_2 , 
            'driver_license_back' => $image_3          
        ];

        return $this->vehicle->insert($requestData);
    }
    
    public function getVehicleById($id){
        return $this->vehicle->where('driver_id',$id)->first();
    }

    public function updateVehicleInfo($request)
    {
        $id = $request->driver_id ;

        $image_1 = $image_2 = $image_3 = '' ;

          // upload_vehicle_image
          if ($request['upload_vehicle_image']) {

            if ($request->hasFile('upload_vehicle_image'))
            {
                $image = $request->file('upload_vehicle_image');
                $image_1 = time().rand(111111,999999).'.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/vehicle/');
                $image->move($destinationPath, $image_1);
            }
          } 
        
                // driver_license_front
                if ($request['driver_license_front']) {
        
                    if ($request->hasFile('driver_license_front'))
                    {
                        $image = $request->file('driver_license_front');
                        $image_2 = time().rand(111111,999999). '.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/vehicle/');
                        $image->move($destinationPath, $image_2);
                    }
                  } 
        
                // driver_license_front
                if ($request['driver_license_back']) {
        
                    if ($request->hasFile('driver_license_back'))
                    {
                        $image = $request->file('driver_license_back');
                        $image_3 = time().rand(111111,999999).'.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('/uploads/vehicle/');
                        $image->move($destinationPath, $image_3);
                    }
                  }  
                
              if( $image_1 !='' ) 
                  { $req['upload_vehicle_image'] = $image_1 ; }
      
              if( $request->vechicle_make !='' ) 
                  { $req['vechicle_make'] = $request->vechicle_make ; }
      
              if( $request->vechicle_model !='' ) 
                  { $req['vechicle_model'] = $request->vechicle_model ; }
      
              if( $request->vechicle_license_plate !='' ) 
                  { $req['vechicle_license_plate'] = $request->vechicle_license_plate ; }
      
              if( $request->vechicle_color !='' ) 
                  { $req['vechicle_color'] = $request->vechicle_color ; }    
      
              if( $request->vechicle_type !='' ) 
                  { $req['vechicle_type'] = $request->vechicle_type ; } 
      
              if( $image_2 !='' ) 
                  { $req['driver_license_front'] = $image_2 ; }    
      
              if( $image_3 !='' ) 
                  { $req['driver_license_back'] = $image_3 ; }    
           
        return $this->vehicle->where('id',$id)->update($req) ;
    }

}