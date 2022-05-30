<?php
namespace App\Helpers;
use App\User;
use App\Models\Notification;
use App\Models\Permissions;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use File;
use Str;
class CommonHelper 
{

public static function getLoginUserPermissions(){
    $permissions = Auth()->user()->permissions;
    $arr = explode(",",$permissions);
    $permission_name =  Permissions::selectRaw('GROUP_CONCAT(route) as routes')
                        ->whereIn('id',$arr)->first();
    return $permission_name['routes'];
}
public static function getAdminSettings($keys){
    $setting =  Settings::where('status','active')->where('name',$keys)->first();
    if(!empty($setting)){
        return $setting['value'];
    }
    return "0";
}
/**
 *Check token
 */
public static function checkToken($token)
{
    $response = User::where('access_token', '=', $token)->first();
    if ($response) {
        return true;
    } else {
        return false;
    }
}


private function is_base64_encoded($str) {

    $decoded_str = base64_decode($str);
    $Str1 = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $decoded_str);
    if ($Str1!=$decoded_str || $Str1 == '') {
       return false;
    }
    return true;
 }
/**
 *get data from token
 */
public static function getUserFromToken($request)
{
    $token = $request->header('Authorization');
    if (Str::startsWith($token, 'Bearer ')) {
        $token = Str::substr($token, 7);
    }
   return User::where('access_token',$token)->first();
    
}

/**
* Function : getImageName
* @param $image path
* @return $image name
*/
public static function getImageName($image_path){
    $image = (explode('/', $image_path)) ;
    return end($image) ; 
}

public static function moveImageFile($file,$folder){
    File::move(Storage::path(('temp')."/".$file), Storage::path(($folder)."/".$file));
}
/**
 * Remove image
 */
public static function removeImage($destination, $image){
    if (\File::exists($destination . '/' . $image)) {
        \File::delete($destination . '/' . $image);
        return true;
    }
    return false;
}
/**
 * Update password
 */
function updatePassword($data)
{
    $response = User::where('password_token', '=', $data['code'])
        ->update(array('password' => bcrypt($data['password']), 'password_token' => null));
    if ($response) {
        return true;
    } else {
        return false;
    }
}



/**
 * search  array values
 * @param type $array
 * @param type $search_list
 * @return type
 */
public static function search($array, $search_list) {
    $result = array();
    foreach ($array as $key => $value) {
        foreach ($search_list as $k => $v) {
            if (!isset($value[$k]) || $value[$k] != $v) {
                continue 2;
            }
        }
        $result[] = $value;
    }
    return $result;
}


/**
 * get Admin detials
*/
public static function getAdminDetails(){
    return  User::where(['role_id' => '1'])->first();
}

/**
 * replace message string
 * @param type $string
 * @param type $replaceString
 * @param type $message
 * @return type
 */
public static function replaceMessage($string,$replaceString, $message){
    return str_replace($string,$replaceString,$message);
}

/**
 * get User information by id
 * @param type $id
 */
public static function getUserDetails($id){
    return User::where(['id' => $id])->first();
}

public static function countRestNotification(){
    $restaurantIds = getAssingedBranchesId();
    return \App\Models\Notification::whereIn('to_id', $restaurantIds)->where(['status' => 'unread','receive_type' => 'restaurant'])->count();
}

public static function countAdminNotification(){
    $admin = getAdminDetails();
    return \App\Models\Notification::where(['to_id' => $admin['id'],'status' => 'unread','receive_type' => 'admin'])->count();
}

function desktopNotification($id){
    $userData = getUserDetails($id);
    $data = array();
    $data['data']['notification']['title'] = "FCM Message";
    $data['data']['notification']['body'] = "This is an FCM Message";
    $data['data']['notification']['icon'] = "/itwonders-web-logo.png";
    $data['data']['notification']['sound'] = "default";
    $data['data']['webpush']['headers']['Urgency'] = "high";
    $data['to'] = $userData['notification_token'];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_POST, 1);
    $headers = array();
    $headers[] = "Authorization: key = AAAA0dXFh7A:APA91bHuq6poKXpulDyOmbUsnrw-sYuKHjO0ZA2vJ5v82UUWySkzqOIA0k2rVVTZ3xefnRai8C9yFuCUffIkm5dDdCbymx6-ail5LXRMzysy5F6AMUkXpDUDrp6_NMSby-jNPAoNPsvL";
    $headers[] = "Content-Type: application/json";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_URL , "https://fcm.googleapis.com/fcm/send");
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($data));
    // curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);
    // curl_setopt($ch,CURLOPT_SSL_VERIFYPEER , false);

    $result = curl_exec($ch);
    $result = json_decode($result,1);
    if (curl_errno($ch))
    echo 'Error:' . curl_error($ch);

    curl_close($ch);

    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/play_sound.php";
                            if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
                                $url = "https://$_SERVER[HTTP_HOST]/play_sound.php";
                            }

    $data = array();
    $params = '';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '?' . $params); //Url together with parameters
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7); //Timeout after 7 seconds
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
}

    /**
     * format date time
     * @param type $string
     * @return type
     */
    public static function dateTimeFormat($date,$format) {
        return Carbon::parse($date)->format($format);
    }
    /**
     * convert date time
     * @param type $string
     * @return type
     */
    public static function convertDateTime($string) {
        return Carbon::parse($string)->format('D M d Y h:i A');
    }
    public static function currentDatetime() {
        return Carbon::now()->format('Y-m-d H:i:s');
    }
    public static function currentDate() {
        return Carbon::now()->format('Y-m-d');
    }
    public static function SubtractDaysFromCurrentDate($days) {
        return Carbon::now()->subDays($days)->format('Y-m-d');
    }
    public static function addDaysFromDate($datetime,$hour){
       return Carbon::parse($datetime)->addHour($hour)->format('Y-m-d H:i:s');
    }
    public static function addHoursFromDate($datetime,$hour){
        return Carbon::parse($datetime)->addHour($hour)->format('Y-m-d H:i:s');
     }
    public static function addMinuteFromDate($datetime,$hour){
        return Carbon::parse($datetime)->addMinute($hour)->format('Y-m-d H:i:s');
     }
    /**
	 * [convertTimeToUTCzone <this function convert string to UTC time zone>]
	 * @param  [type] $str          [description]
	 * @param  [type] $userTimezone [description]
	 * @param  string $format       [description]
	 * @return [type]               [description]
	 */
    public static function convertTimeToUTCzone($str, $userTimezone, $format = ''){
		try{
			    if(!$format){
			      	$format = config('constants.SAVE_DATE_TIME_DB');
			    }
			    $new_str = new DateTime($str, new DateTimeZone(  $userTimezone  ) );
			    $new_str->setTimeZone(new DateTimeZone('UTC'));
			    return $new_str->format( $format);
		}catch(\Exception $e)
		{
			return ['success'=>false, 'message'=>'', 'error'=>[array('message'=>$e->getMessage())], 'data' => []];
		}
	}

    /**
	 * [convertTimeToUSERzone <this function converts string from UTC time zone to current user timezone>]
	 * @param  [type] $str          [description]
	 * @param  [type] $userTimezone [description]
	 * @param  string $format       [description]
	 * @return [type]               [description]
	 */
    public static function convertTimeToUSERzone($str, $userTimezone, $format = ''){
		try{
				if(empty($str)) {
			        return '';
				}

			    if(!$format) {
			    	$format = config('constants.SHOW_DATE_TIME');
			    }
			    $new_str = new DateTime($str, new DateTimeZone('UTC'));
			    $new_str->setTimeZone(new DateTimeZone( $userTimezone ));
				return $new_str->format( $format);

		}catch(\Exception $e)
		{
			return ['success'=>false, 'message'=>'', 'error'=>[array('message'=>$e->getMessage())], 'data' => []];
		}
    }

    public static function getTransactionId()
    {
        mt_srand((double)microtime()*10000);
        $charid = md5(uniqid(rand(), true));
        $c = unpack("C*",$charid);
        $c = implode("",$c);

        return substr($c,0,20);
    }
    public static function getPassword($password)
    {
        return sha1($password);
    }

    
   
    public static function CurlCallPostMethod($url,$postData)
    {
      
        $jsonData = json_encode($postData);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
       
        $jsonData =json_decode($result);
        return $jsonData;
    }
    public static function imageCompressUploads($request,$type)
    {
        $image = $request->file('image');
        $uploadImg = time().rand(111111,999999).'.'.$image->getClientOriginalExtension();
        $destinationPath = storage_path('app/'.$type);
        $img = Image::make($image->getRealPath());
        $img->resize(200, 200, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$uploadImg);
        $image->move($destinationPath, $uploadImg);
        return $type."/".$uploadImg;
    }
    public static function sendPushNotification($data) {
        if(!empty($data['user_id'])){
            $userDevice = User::where('id', $data['user_id'])->select('id','device_token')->first();
                if(!empty($userDevice['device_token'])){
                    $notifaction['user_id'] = $data['user_id'];
                    $notifaction['title']   = $data['title'];
                    $notifaction['img']     = $data['img'];
                    $notifaction['type']    = $data['type'];
                    $notifaction['title']   = $data['title'];
                    $notifaction['msg']     = $data['msg'];
                    $notifaction['order_id']= $data['order_id'];
                    $notifaction['user_type'] = 'user';
                    $notifaction['date'] = date("jS F Y G:i A");
                    $result = Notification::create($notifaction);
                    if(!empty($result)){
                      
                        $deviceId = $userDevice['device_token'];
                        $API_ACCESS_KEY = getenv("API_ACCESS_KEY");
                        if (!isset($API_ACCESS_KEY)) {
                            return false;
                        }
                        
                        $url = 'https://fcm.googleapis.com/fcm/send';
                        $fields = array("to" => $deviceId, "notification" => array( "title" =>$data['title'],'image'=>$data['img'], "body" => $data['msg'],'vibrate' => 1,'sound' => 1 ,'priority' => 1,'visibility'=> 1,'no-cache' => 1,'force-start' => 1,'order_id'=>$data['order_id']));
                        Log::debug('notification fields', ['fields' => $fields]);
                        $headers = array(
                            'Authorization: key='.$API_ACCESS_KEY,
                            'Content-Type: application/json'
                        );
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                        $result = curl_exec($ch);
                        $result = json_decode($result,TRUE);
                        Log::debug('notification check', ['data' => $result]);
                        if ($result === FALSE) {
                            die('Curl failed: ' . curl_error($ch));
                        }
                        if ($result['success'] == 1) {
                            curl_close($ch);
                        } else {
                            curl_close($ch);
                        }
                    }
                }
        
            return true;
        }
    }
    public static function sendToAllNotification($deviceToken,$data){
        if(!empty($deviceToken)){
             $deviceId = $deviceToken;
                $API_ACCESS_KEY = getenv("API_ACCESS_KEY");
                if (!isset($API_ACCESS_KEY)) {
                    return false;
                }
                
                $url = 'https://fcm.googleapis.com/fcm/send';
                $fields = array("to" => $deviceId, "notification" => array( "title" =>$data['title'],'image'=>$data['img'], "body" => $data['msg'],'vibrate' => 1,'sound' => 1 ,'priority' => 1,'visibility'=> 1,'no-cache' => 1,'force-start' => 1));
                Log::debug('notification fields', ['fields' => $fields]);
                $headers = array(
                    'Authorization: key='.$API_ACCESS_KEY,
                    'Content-Type: application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                $result = json_decode($result,TRUE);
                Log::debug('notification check', ['data' => $result]);
                if ($result === FALSE) {
                    die('Curl failed: ' . curl_error($ch));
                }
                if ($result['success'] == 1) {
                    curl_close($ch);
                } else {
                    curl_close($ch);
                }
           
        }
    }

function calculate_time_span($date){
    $seconds  = strtotime($date) - strtotime(date('Y-m-d H:i:s'));

        $months = floor($seconds / (3600*24*30));
        $day = floor($seconds / (3600*24));
        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours*3600)) / 60);
        $secs = floor($seconds % 60);

        if($seconds < 60)
            $time = $secs." seconds ago";
        else if($seconds < 60*60 )
            $time = $mins." min ago";
        else if($seconds < 24*60*60)
            $time = $hours." hours ago";
        else if($seconds < 24*60*60)
            $time = $day." day ago";
        else
            $time = $months." month ago";

        return $time;
    }
    /**
     * Function : genrateOTPCode
     * Desc : genrate otp code
     * @return string
     */
    public static function genrateOTPCode(){
        return "2468";
       // return rand(1111,9999);
    }
}
