<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiController extends Controller
{
    
    public function pointproduct(){
        
        $d = DB::select("SELECT `mst_item`.* FROM `ggr_rel_item_pick`,`mst_item` WHERE `mst_item`.`item_id`= `ggr_rel_item_pick`.`item_id`");
        return response()->json($d);
     }


    public function shopcode(Request $request){
        $sid = $request->scode;
        $s = DB::select("SELECT * FROM `mst_shop` WHERE `mst_shop`.`shop_pin`=$sid");
        if(count($s)==0){
            return response()->json(['id' => 0]);
        }else{
            return response()->json(['id' => 1]);
        }
     }    


  
    public function pointnotification(Request $request){
        $uid = $request->uid;
        $currentDateTime = Carbon::now();
        $date_time = $currentDateTime->timestamp;;
        $rest_time = strtotime('-1 year', $date_time);
    
        $point_use = 0;
        $point_list = [];
    
        $sql = DB::select("SELECT `point_date`, `point_point` FROM `dat_point` WHERE `point_date` >= '$rest_time' AND `point_point` > 0 AND `user_id` = $uid");
       // dd("SELECT `point_date`, `point_point` FROM `dat_point` WHERE `point_date` >= '$rest_time' AND `point_point` > 0 AND `user_id` = $uid");
       
        // $par = [$rest_time, $uid];
        // $res = sql($sql, $par); // Assume sql() function is defined and returns a result set object
        if ($sql) {
    
            foreach ($sql as $row) {
                $time = strtotime('+1 year', $row->point_date);
                $date = date('Y年m月d日', $time);
                if (!isset($point_list[$date])) {
                    $point_list[$date]['point'] = 0;
                }
                $point_list[$date]['point'] += $row->point_point;
                $point_list[$date]['time'] = $time;
                
            }
    
            $sql2 = DB::select("SELECT SUM(point_point) as point_use FROM dat_point WHERE point_date >= $rest_time AND point_point < 0 AND user_id = $uid");
            // $res2 = sql($sql, $par);

            
            if (count($sql2)>0) {
                $point_use = $sql2[0]->point_use;

                
    
                foreach ($point_list as $date => $row) {
                    if ($row['point'] + $point_use > 0) {
                        $point_list[$date]['point'] = $row['point'] + $point_use;
                        break;
                    } else {
                        $point_use += $row['point'];
                         unset($point_list[$date]);
                        
                    }
                }
    
                foreach ($point_list as $date => $row) {
                    if ($row['time'] <= $date_time or $row['time'] > strtotime('+30 day')) {
                         unset($point_list[$date]);
                         
                        
                    }
                }
    
            }
            $point_list = array_values($point_list);
    
            return response()->json($point_list);
    
        } else {
            // Handle error here
            return response()->json($point_list);
        }
    }
    

    public function usepoint(Request $request){
        $sid = $request->sid;
        $d = DB::select("SELECT `mst_shop`.`shop_id`,`mst_shop`.`shop_name`,`mst_shop`.`shop_pin`,`mst_shop`.`shop_point_num` FROM `mst_shop` WHERE `mst_shop`.`shop_pin`=$sid");
        return response()->json($d);
     }

    public function userpoint(Request $request){
        $uid = $request->uid;

        $d = DB::select("SELECT SUM(point_point)as total FROM dat_point WHERE `user_id`=$uid");
        return response()->json($d);
     }



    public function shop(){
       
        $d = DB::select("SELECT `mst_shop`.`shop_id`,`mst_shop`.`shop_name`,`mst_shop`.`shop_pin`,`mst_shop`.`shop_pref`,`mst_shop`.`shop_code`,`mst_shop`.`shop_addr`,`mst_city`.`city_name`,`mst_pref`.`pref_name`,`mst_shop`.`shop_tel1` FROM `mst_shop`,`rel_site_shop`,`mst_pref`,`mst_city` WHERE `mst_shop`.`shop_id`= `rel_site_shop`.`shop_id` AND `rel_site_shop`.`site_id` ='1'AND `mst_shop`.`shop_pref`=`mst_pref`.`pref_id` AND `mst_shop`.`shop_city`= `mst_city`.`city_id` AND `mst_city`.`pref_id`=`mst_shop`.`shop_pref` AND `mst_shop`.`shop_stop`=0  order by `mst_shop`.`shop_pref`  AND `mst_shop`.`shop_city` ASC ");
        return response()->json($d);
     }
     
     public function getshop(Request $request){
         $pid = $request->pid;

         if($pid == '0'){

             $dd = DB::select("SELECT `mst_shop`.`shop_id`,`mst_shop`.`shop_name`,`mst_shop`.`shop_pin`,`mst_shop`.`shop_pref`,`mst_shop`.`shop_code`,`mst_shop`.`shop_addr`,`mst_city`.`city_name`,`mst_pref`.`pref_name`,`mst_shop`.`shop_tel1` FROM `mst_shop`,`rel_site_shop`,`mst_pref`,`mst_city` WHERE `mst_shop`.`shop_id`= `rel_site_shop`.`shop_id` AND `rel_site_shop`.`site_id` ='1'AND `mst_shop`.`shop_pref`=`mst_pref`.`pref_id` AND `mst_shop`.`shop_city`= `mst_city`.`city_id` AND `mst_city`.`pref_id`=`mst_shop`.`shop_pref` AND `mst_shop`.`shop_stop`=0  order by  `mst_shop`.`shop_pref` AND  `mst_shop`.`shop_city` ASC");
         }else{

             $dd = DB::select("SELECT `mst_shop`.`shop_id`,`mst_shop`.`shop_name`,`mst_shop`.`shop_pin`,`mst_shop`.`shop_pref`,`mst_shop`.`shop_code`,`mst_shop`.`shop_addr`,`mst_city`.`city_name`,`mst_pref`.`pref_name`,`mst_shop`.`shop_tel1` FROM `mst_shop`,`rel_site_shop`,`mst_pref`,`mst_city` WHERE `mst_shop`.`shop_id`= `rel_site_shop`.`shop_id` AND `rel_site_shop`.`site_id` ='1' AND `shop_pref` = '$pid' AND `mst_shop`.`shop_pref`=`mst_pref`.`pref_id` AND `mst_shop`.`shop_city`= `mst_city`.`city_id` AND `mst_city`.`pref_id`=`mst_shop`.`shop_pref`AND `mst_shop`.`shop_stop`=0  order by `mst_shop`.`shop_pref`  AND `mst_shop`.`shop_city` ASC");
         }
        // $d = DB::select("SELECT * FROM `mst_shop`");
          return response()->json($dd);
      }

     public function point(Request $res){
         $uid=$res->uid;
         //$d = DB::select("SELECT `dat_point`.`shop_id`,`dat_point`.`resv_id`,`dat_point`.`order_id`,`dat_point`.`point_type`,`dat_point`.`point_point`,`dat_point`.`point_date`,`mst_shop`.`shop_name`,`mst_shop`.`shop_code` FROM `dat_point`,`mst_shop`WHERE `dat_point`.`shop_id`=`mst_shop`.`shop_id` AND`dat_point`.`user_id` = '$uid' ORDER BY `dat_point`.`point_date` DESC");
        // $d = DB::select("SELECT `dat_point`.`shop_id`, `dat_point`.`resv_id`, `dat_point`.`order_id`, `dat_point`.`point_type`, `dat_point`.`point_point`, `dat_point`.`point_date`, IF(`dat_point`.`shop_id` = 0, NULL, `mst_shop`.`shop_name`) AS `shop_name`, IF(`dat_point`.`shop_id` = 0, NULL, `mst_shop`.`shop_code`) AS `shop_code` FROM `dat_point` LEFT JOIN `mst_shop` ON `dat_point`.`shop_id` = `mst_shop`.`shop_id` WHERE `dat_point`.`user_id` = '$uid' AND `dat_point`.`shop_id` <> 0 ORDER BY `dat_point`.`point_date` DESC");
         $d = DB::select("SELECT `dat_point`.`shop_id`, `dat_point`.`resv_id`, `dat_point`.`order_id`, `dat_point`.`point_type`, `dat_point`.`point_point`, `dat_point`.`point_date`, IF(`dat_point`.`shop_id` = 0, NULL, `mst_shop`.`shop_name`) AS `shop_name`, IF(`dat_point`.`shop_id` = 0, NULL, `mst_shop`.`shop_code`) AS `shop_code` FROM `dat_point` LEFT JOIN `mst_shop` ON `dat_point`.`shop_id` = `mst_shop`.`shop_id` WHERE `dat_point`.`user_id` = '$uid' ORDER BY `dat_point`.`point_date` DESC");

         return response()->json($d);

         }

     Public function booking(Request $res){
         $uid=$res->uid;
         $d = DB::select("SELECT `dat_resv`.`resv_plan`,`dat_resv`.`resv_date`,`dat_resv`.`resv_name`,`dat_resv`.`resv_price1`,`dat_resv`.`resv_total_point`,`dat_resv`.`resv_men1`,`dat_resv`.`resv_men2`,`mst_site`.`site_name`,`mst_shop`.`shop_name`,`mst_shop`.`shop_addr`,`mst_shop`.`shop_tel1`,`mst_site`.`site_domain`,`dat_resv`.`resv_status1_date`,`mst_city`.`city_name`,`mst_pref`.`pref_name` FROM `dat_resv`,`mst_site`,`mst_shop`,`mst_city`,`mst_pref`WHERE `mst_site`.`site_id`=`dat_resv`.`resv_site` AND`dat_resv`.`resv_user` = '$uid' AND  `mst_shop`.`shop_city`= `mst_city`.`city_id` AND `mst_city`.`pref_id`=`mst_shop`.`shop_pref` AND `mst_shop`.`shop_pref`=`mst_pref`.`pref_id` AND  `dat_resv`.`shop_id`=`mst_shop`.`shop_id` ORDER BY `dat_resv`.`resv_date` DESC");
          return response()->json($d);

     }


     Public function product(Request $res){
         $uid=$res->uid;
         $d = DB::select("SELECT `dat_order_item`.`order_item`,`dat_order`.`order_date`,`dat_order_item`.`order_name`,`dat_order_item`.`order_total_price2`,`dat_order_item`.`order_total_point`,`mst_site`.`site_name`,`mst_site`.`site_domain`,`mst_shop`.`shop_name`,`mst_shop`.`shop_tel1`,`mst_shop`.`shop_addr`,`mst_city`.`city_name`,`mst_pref`.`pref_name` FROM `dat_order_item`,`dat_order`,`mst_site`,`mst_shop`,`mst_pref`,`mst_city` WHERE `dat_order`.`shop_id`=`dat_order_item`.`shop_id` AND `dat_order_item`.`order_id`= `dat_order`.`order_id`   AND `dat_order`.`order_user` = '$uid' AND `mst_site`.`site_id` = `dat_order`.`order_site` AND `mst_shop`.`shop_id` = `dat_order`.`shop_id` AND  `mst_shop`.`shop_city`= `mst_city`.`city_id` AND `mst_city`.`pref_id`=`mst_shop`.`shop_pref` AND `dat_order`.`shop_id`=`mst_shop`.`shop_id` AND `mst_shop`.`shop_pref`=`mst_pref`.`pref_id` ORDER BY `dat_order`.`order_date` DESC  ");
         return response()->json($d);


      }
      Public function oneshopproduct(Request $res){
        $uid=$res->uid;
        $sid=$res->sid;
        $d = DB::select("SELECT * FROM `dat_order_item`,`dat_order`,`mst_shop`,`mst_pref`,`mst_city` WHERE `dat_order`.`shop_id`=`dat_order_item`.`shop_id` AND `dat_order_item`.`order_id`= `dat_order`.`order_id`   AND `dat_order`.`order_user` = '$uid'  AND `mst_shop`.`shop_id` = `dat_order`.`shop_id` AND  `mst_shop`.`shop_city`= `mst_city`.`city_id` AND `mst_city`.`pref_id`=`mst_shop`.`shop_pref` AND `dat_order`.`shop_id`=`mst_shop`.`shop_id` AND `mst_shop`.`shop_pref`=`mst_pref`.`pref_id` AND `dat_order_item`.`shop_id`=$sid ORDER BY `dat_order`.`order_date` DESC  ");
        return response()->json($d);


     }

      Public function userinfo(Request $res){

         $uid=$res->uid;
         $s = DB::select("SELECT * FROM `mst_user` WHERE  `user_id` = '$uid'");
         return response()->json($s);
      }

     Public function ken(){
         $d = DB::select("SELECT `mst_pref`.`pref_id`,`mst_pref`.`pref_name` FROM `mst_pref`");
         $dd=[['pref_id' => 0,'pref_name' => '全国',]];
         $result = array_merge($dd,$d);
         return response()->json($result);
     }

     public function login(Request $request){

         $email = $request->email;
         $pass = $request->pass;


         $data = DB::select("SELECT * FROM `mst_user` WHERE `user_mail` = '$email'");
         if(count($data)!=0){
             $dbpass = $data['0']->user_pass;
             $c = ($pass==$dbpass);
             if($c=='true'){
                 return response()->json(['status' => 1]);
             }else{
                 return response()->json(['status' => 0]);
             }

         }else{
             return response()->json(['status' => 0]);
         }

     }

     public function get_user_id(Request $request){
         $email = $request->email;

         $s = DB::select("SELECT * FROM `mst_user` WHERE `user_mail` = '$email'");

         if(count($s)==0){
             return response()->json(['id' => 0]);
         }else{
             return response()->json(['id' => $s['0']->user_id]);
         }

     }

     public function get_user_data(Request $request){
        $uid = $request->uid;
        $s = DB::select("SELECT * FROM `mst_user` WHERE `user_id` = '$uid'");
        return response()->json($s);
        //return ShopResource::collection($s);
        
    }



     Public function updatepass(Request $res){
         $pass = $res->pass;
         $id = $res->id;

         DB::update("UPDATE `mst_user` SET `user_pass` = '$pass' WHERE `user_id` = '$id'");
         return response()->json(['status' => 1]);
      }

      Public function updatephone(Request $res){
        $phone = $res->phone;
        $id = $res->id;

        DB::update("UPDATE `mst_user` SET `user_tel` = '$phone' WHERE `user_id` = '$id'");
        return response()->json(['status' => 1]);
     }

     Public function changeemail(Request $res){
        $email = $res->email;
        $id = $res->id;

        DB::update("UPDATE `mst_user` SET `user_mail` = '$email' WHERE `user_id` = '$id'");
        return response()->json(['status' => 1]);
     }

     Public function changeuser(Request $res){
         $id = $res->id;
         $username=$res->username;
         DB::update("UPDATE `mst_user` SET `user_name` = '$username'  WHERE `user_id` = '$id'");

      }

     Public function changeadrs(Request $res){
         $zip = $res->zip;
         $ken =$res->ken;
         $address_1 = $res->address_1;
         $address_2 = $res->address_2;
         $phone=$res->phone;
         $id = $res->id;

         DB::update("UPDATE `mst_user` SET `user_zip` = '$zip',`user_addr` = '$address_1',`user_addr2` = '$address_2', `user_pref`= '$ken' ,`user_tel`='$phone' WHERE `user_id` = '$id'");
         return response()->json(['status' => 1]);
     }


     /// test

     public function orderhis(){
         
         $d =  DB::select("SELECT * FROM `dat_order_item`,`dat_order`WHERE `dat_order_item`.`order_id`=`dat_order`.`order_id` AND `dat_order`.`order_user`=$uid AND `dat_order`.`shop_id`=`dat_order_item`.`shop_id`AND `dat_order`.`order_site`=6");
         return response()->json($d);
     }

     // product view get shop id 
     public function shopproduct(Request $request){
        $sid=$request->sid;
        $d = DB::select("SELECT * FROM `mst_item` WHERE `mst_item`.`item_shop`=$sid And `mst_item`.`item_stop`=0 ORDER BY `mst_item`.`item_sort` ASC");
        return response()->json($d);
    }

    public function site(Request $request){
        $sid=$request->sid;
        $d = DB::select("SELECT * FROM `mst_site` WHERE  `mst_site`.`site_stop`=0");
        return response()->json($d);
    }

    public function getproduct(Request $request){
        $pid = $request->pid;
        $d= DB::select("SELECT * FROM `mst_item` WHERE `item_id` = '$pid'");
        return response()->json($d);
    }
    //cart

    public function shopcard(Request $request){
        $uid = $request->uid;
        $sid = $request->sid;
        $d =  DB::select("SELECT `mst_item`.*,`app_cart`.`qty` FROM `app_cart`,`mst_item` WHERE `app_cart`.`item_id` = `mst_item`.`item_id`AND `user_id` = $uid AND `status`=1 AND`shop_id`=$sid ");
        return response()->json($d);
        
    }

    public function addtocart(Request $request){
        $uid = $request->uid;
        $product_id= $request->product_id;
        $qty = $request->qty;
        $sid=$request->sid;


        $q = DB::select("SELECT * From `app_cart` where `shop_id` = '$sid' AND `user_id` = '$uid' AND `item_id` = '$product_id'");

        if(count($q)>0){
            $d = DB::update("UPDATE `app_cart` SET `qty` = `qty` + '$qty' WHERE `shop_id` = '$sid' AND `user_id` = '$uid' AND `item_id` = '$product_id'");
        }else{
        $d = DB::insert("INSERT INTO `app_cart`(`shop_id`,`user_id`, `item_id`, `qty`, `status`) VALUES ('$sid','$uid', '$product_id', '$qty', 1)");
        }
        
        
        if($d == true){
            return response()->json(1);
        }else{
            return response()->json(0);
        }

    }
    public function cart(Request $request){
        $uid = $request->uid;

        $a = DB::select("SELECT DISTINCT `shop_id` FROM `app_cart` WHERE `user_id` = '$uid' AND `status`=1 AND `shop_id`=26");
        $data = [];
        foreach($a as $aa){ 

            $dd = [];
            $sid = $aa->shop_id;
            $d =  DB::select("SELECT `app_cart`.*, `mst_item`.`item_price`, `mst_item`.`item_tax`, `mst_item`.`item_point`,`mst_item`.`item_name`  FROM `app_cart`,`mst_item` WHERE `user_id` = '$uid' AND `shop_id` = '$sid' AND `status`=1 AND `mst_item`.`item_id` = `app_cart`.`item_id`");
            $s =  DB::select("SELECT * FROM `mst_shop` WHERE `shop_id` = $sid");

            $shopname = $s[0]->shop_name;
            

            array_push($data, ['shopname' => $shopname,'data' => $d]);
        }

        return response()->json($data);
    }
    public function deletecart(Request $request){
        $id = $request->id;
        $d =  DB::select("DELETE FROM `app_cart` WHERE `id` =$id;");
        return response()->json($d);
        
    }
    public function clearcard(Request $request){
        $id = $request->uid;
        $sid = $request->sid;
        $d =  DB::select("DELETE FROM `app_cart` WHERE `user_id` =$id AND `shop_id` =$sid  ");
        return response()->json($d);
        
    }
    public function updatecart(Request $request){
        $id = $request->id;
        $qty=$request->qty;
        $d =  DB::select("UPDATE `app_cart` SET qty=$qty  WHERE `id` =$id;");
        return response()->json($d);
        
    }
    public function cartsize(Request $request){
        $uid = $request->uid;
        
        $d =  DB::select("SELECT `app_cart`.`id` FROM `app_cart` WHERE `user_id` = $uid AND `status`=1");
        return response()->json($d);
        
    }

    public function shopcategory(Request $request){
        $uid = $request->uid;
        
        $d =  DB::select("SELECT * FROM `web_mst_page` JOIN `mst_item` ON FIND_IN_SET(`mst_item`.`item_id`, `web_mst_page`.`page_item_list1`) WHERE `web_mst_page`.`shop_id` = 26 AND `mst_item`.`item_stop`=0 ORDER BY `mst_item`.`item_sort` ASC");
        return response()->json($d);
        
    }
    public function shopcategorytwo(Request $request){
        $uid = $request->uid;
        
        $d =  DB::select("SELECT * FROM `web_mst_page` JOIN `mst_item` ON FIND_IN_SET(`mst_item`.`item_id`, `web_mst_page`.`page_item_list2`) WHERE `web_mst_page`.`shop_id` = 26 AND `mst_item`.`item_stop`=0 ORDER BY `mst_item`.`item_sort` ASC");
        return response()->json($d);
        
    }
    public function shopnews(Request $request){
        $sid = $request->sid;
        
        $d =  DB::select("SELECT * FROM `web_mst_news` WHERE `web_mst_news`.`shop_id` =$sid AND news_stop=0");
        return response()->json($d);
        
    }
    public function category1(Request $request){
        $sid = $request->sid;
        
        $d =  DB::select("SELECT * FROM `web_mst_page` JOIN `mst_plan` ON FIND_IN_SET(`mst_plan`.`plan_id`, `web_mst_page`.`page_item_list1`) WHERE `web_mst_page`.`shop_id` = $sid AND `mst_plan`.`plan_stop`=0 ORDER BY `mst_plan`.`plan_sort` ASC");
        return response()->json($d);
        
    }
    public function category2(Request $request){
        $sid = $request->sid;
        
        $d =  DB::select("SELECT * FROM `web_mst_page` JOIN `mst_plan` ON FIND_IN_SET(`mst_plan`.`plan_id`, `web_mst_page`.`page_item_list2`) WHERE `web_mst_page`.`shop_id` = $sid AND `mst_plan`.`plan_stop`=0 ORDER BY `mst_plan`.`plan_sort` ASC");
        return response()->json($d);
        
    }
    public function category3(Request $request){
        $sid = $request->sid;
        
        $d =  DB::select("SELECT * FROM `web_mst_page` JOIN `mst_plan` ON FIND_IN_SET(`mst_plan`.`plan_id`, `web_mst_page`.`page_item_list3`) WHERE `web_mst_page`.`shop_id` = $sid AND `mst_plan`.`plan_stop`=0 ORDER BY `mst_plan`.`plan_sort` ASC");
        return response()->json($d);
        
    }
    public function category4(Request $request){
        $sid = $request->sid;
        
        $d =  DB::select("SELECT * FROM `web_mst_page` JOIN `mst_plan` ON FIND_IN_SET(`mst_plan`.`plan_id`, `web_mst_page`.`page_item_list4`) WHERE `web_mst_page`.`shop_id` = $sid AND `mst_plan`.`plan_stop`=0 ORDER BY `mst_plan`.`plan_sort` ASC");
        return response()->json($d);
        
    }

    public function bookingdate(Request $request){
        $pid = $request->pid;
        
        $d =  DB::select("SELECT * FROM `mst_plan_price`  WHERE `mst_plan_price`.`plan_id` = $pid ");
        return response()->json($d);
        
    }
//     public function bookingdate(Request $request)
// {
//     $pid = $request->pid;

//     $d =  DB::select("SELECT * FROM `mst_plan_price`  WHERE `mst_plan_price`.`plan_id` = $pid ");

//     foreach ($d as $item) {
//         $item->price_date = date('Y年n月j日', $item->price_date);
//     }

//     return response()->json($d);
// }

public function plan (Request $request){
    $pid = $request->pid;
    $d = DB::select("SELECT * FROM `mst_plan`WHERE `mst_plan`.`plan_id`= $pid AND `mst_plan`.`plan_stop`=0");
    return response()->json($d);
 }

//  public function shopcoupon (Request $request){
//     $sid = $request->sid;
//     $d = DB::select("SELECT * FROM `mst_coupon` WHERE `mst_coupon`.`coupon_shop`=$sid AND `mst_coupon`.`coupon_stop`=0");
//     return response()->json($d);
//  }

// public function shopcoupon(Request $request){
//     $sid = $request->sid;
//     $uid= $request->uid;

//     // Get the current time
//     $currentTime = Carbon::now()->timestamp;

//     // Calculate the timestamp 72 hours ago
//     $time72HoursAgo = $currentTime - (72 * 3600); // 72 hours * 3600 seconds per hour

//     $d = DB::select("SELECT * FROM `mst_coupon`,`dat_coupon` WHERE `mst_coupon`.`coupon_shop`=`dat_coupon`.`shop_id`   AND`mst_coupon`.`coupon_shop` = $sid AND `dat_coupon`.`user_id`=$uid AND `mst_coupon`.`coupon_stop` = 0 AND `dat_coupon`.`coupon_used_time` <= $time72HoursAgo");
    
//     return response()->json($d);
// }
public function shopcoupon(Request $request){
    $sid = $request->sid;
    $userId = $request->uid;
    $currentTime = Carbon::now();
    //echo($sid);
    //echo($userId);
   // Assuming $currentTime is an instance of Carbon
    $currentTime = Carbon::now();
    $time72HoursAgo = $currentTime->subHours(72)->timestamp;
    
   // create the sql query for filtering coupon
    $d = DB::select("SELECT * FROM `dat_coupon` WHERE `user_id` = ? AND `coupon_used_time` < ?", [$sid, $time72HoursAgo]);
    return response()->json($d);
   /* $d = DB::select("
        SELECT mc.*
        FROM `mst_coupon` mc
        LEFT JOIN `dat_coupon` dc ON mc.`coupon_id` = dc.`coupon_id`  
        WHERE mc.`coupon_shop` = $sid
        AND mc.`coupon_stop` = 0
        AND mc.`coupon_stamp` = 0
       
        AND ( dc.`user_id` IS NULL OR dc.`coupon_used_time` <= $time72HoursAgo  ) ORDER BY mc.`coupon_sort` ASC "
        // AND  dc.`coupon_id`!= mc.`coupon_id` "
        //AND dc.`user_id`= $userId "
    //     AND (
    //         dc.`user_id` IS NULL 
    //         OR dc.`coupon_used_time` => :time72HoursAgo
    //     )
    // ", [
    //     'time72HoursAgo' <= $time72HoursAgo,
   // ]
);*/


   // return response()->json($d);
}

 public function shopmstcategory(Request $request){
    $sid = $request->sid;
    $cid=$request->seosonid;
    $d = DB::select("SELECT * FROM `web_mst_cate` JOIN `web_mst_type` ON FIND_IN_SET(`web_mst_cate`.`cate_id`, `web_mst_type`.`type_cate`) WHERE `web_mst_cate`.`shop_id`=$sid AND `web_mst_type`.`type_id`=$cid  AND `web_mst_cate`.`cate_stop`=0 ORDER BY `web_mst_cate`.`cate_sort` ASC");
    return response()->json($d);
 }

 public function shopmstcategoryview (Request $request){
    $sid = $request->sid;
    $cid = $request->cid;
    $d = DB::select("SELECT * FROM `mst_plan` WHERE `mst_plan`.`plan_shop`=$sid AND  FIND_IN_SET($cid,`mst_plan`.`plan_web_cate`) AND `mst_plan`.`plan_stop`=0 ORDER BY `mst_plan`.`plan_sort` ASC");
    return response()->json($d);
 }

 public function shopevent (Request $request){
    $sid = $request->sid;
    $d = DB::select("SELECT * FROM `web_mst_event` WHERE `web_mst_event`.`shop_id`=$sid AND `web_mst_event`.`event_stop`=0 ORDER BY `web_mst_event`.`event_sort` ASC");
    return response()->json($d);
 }
 public function shopeventcate (Request $request){
    $sid = $request->sid;
    $cid = $request->cid;
    $d = DB::select("SELECT * FROM `mst_plan` WHERE  `mst_plan`.`plan_shop`=$sid  AND FIND_IN_SET($cid,`mst_plan`.`plan_web_event`)   AND `mst_plan`.`plan_stop`=0 ORDER BY `mst_plan`.`plan_sort` ASC");
    return response()->json($d);
 }
 public function pointcheck (Request $request){
    $sid = $request->sid;
    $d = DB::select("SELECT `mst_shop`.`shop_app_point` FROM `mst_shop` WHERE  `mst_shop`.`shop_id`=$sid");
    return response()->json($d);
 }

 public function holiday (){
    $d = DB::select("SELECT * FROM `mst_holiday` ");
    return response()->json($d);
 }

 public function addstamp (){
    
    $d = DB::select("SELECT * FROM `mst_holiday` ");
    return response()->json($d);
 }

 public function viewShopCoupon(Request $request)
 
 {
    $coupon_id = $request->coupon_id;
    $userId = $request->uid;
   // Assuming $currentTime is an instance of Carbon
    $date = now()->format('Y-m-d');
    echo $date;
    
   // create the sql query for filtering coupons
    $d = DB::select("SELECT * FROM `mst_coupon` WHERE `coupon_id` = ? AND `coupon_end_date` > ?", [$coupon_id, $date]);
    return response()->json($d);
   
 }

 public function viewStampCoupon(Request $request){
    $user_id = $request->uid;
    $stamp_count = $request->stamp_count;

    $userStampData = DB::select("SELECT * FROM `dat_user_stamp` WHERE `stamp_count` = ?", [$stamp_count]);
    $couponData = DB::select("SELECT * FROM `dat_coupon` WHERE `user_id` = ?", [$user_id]);

    foreach ($userStampData as $userStamp) {
        $stampCount = $userStamp->stamp_count;

        if ($stampCount < 12) {
            $z = $stampCount / 6;

            if ($z == 1) {
                foreach ($couponData as $coupon) {
                    $userId = $coupon->user_id;
                    $shopId = $coupon->shop_id;

                    if ($userId == $shopId) {
                        return response()->json($couponData);
                    }
                }
            }
        } else {
            $z = $stampCount / 6;
            $y = $stampCount / 12;
            $t = $z - $y;

            if ($t > 0) {
                foreach ($couponData as $coupon) {
                    $userId = $coupon->user_id;
                    $shopId = $coupon->shop_id;

                    if ($userId == $shopId) {
                        return response()->json($couponData);
                    }
                }
            }
        } 
           
    }

    return response()->json([]);
}
}