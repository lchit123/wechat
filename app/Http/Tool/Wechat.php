<?php

namespace  App\Http\Tool;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
class Wechat{
	//标签列表
	  public function label_lists(){

  		$data='https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->access_token();
  		// dd($data);
  		// 
  		$d=$this->curl_get($data);
  		// dd($d);
  		$do=json_decode($d,1);
  		// dd($do);
  		return $do;
  }

 public function access_token(){

   	if (Cache::has('access_token_key')) {
   		//有的话去缓存拿
   		// dd(Cache::has('access_token_key'));
  		$access_token=Cache::get('access_token_key');
  		// echo $access_token;
  		
   	}else{
   		//没有通过微信接口拿
   		$access_toke=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET'));
   		// dd($access_token);
   		$access_re=json_decode($access_toke,1);
   		// dd($access_re);
   		$access_token=$access_re['access_token'];
   		$expires_in=$access_re['expires_in'];
   		//加入缓存
   		Cache::put('access_token_key',$access_token,$expires_in);
   		// dd($a);
   	}
   	return $access_token;
  
   	
   }
   //curl发送post
   public function curl_post($url,$data)
    {
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($curl,CURLOPT_POST,true);  //发送post
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        $data = curl_exec($curl);
        $errno = curl_errno($curl);  //错误码
        $err_msg = curl_error($curl); //错误信息
        curl_close($curl);
        return $data;
    }
       //curl发送get
     public function curl_get($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}