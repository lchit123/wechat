<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Tool\Wechat;
use Illuminate\Support\Facades\Cache;
use DB;
class EventController extends Controller
{
	public $wechat;
    public function __construct(Wechat $wechat)
    {
        $this->wechat = $wechat;
    }
	public function index(Request $request){
		$access_token=$this->wechat->access_token();
	
	$info=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access_token&next_openid=");
		// dd($info);
		$in=json_decode($info,1);
		$info=[];
		// dd($info);
		foreach ($in['data']['openid'] as $k => $v) {
		$opid=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$v&lang=zh_CN");
		$info[]=json_decode($opid,1);
		// dd($info);
		// $data=[
		// 		'openid'=>$re['openid'],
		// 		'city'=>$re['city'],
		// 		'nickname'=>$re['nickname'],
		// 		'headimgurl'=>$re['headimgurl']
		// ];
		// $res=DB::table('wechat')->insert($data);
		

			}

		$req=$request->all();
		return view('wechat.index',['info'=>$info,'req'=>$req]);
			
	}
	//登陆显示页面
	public function login(){
		return view('wechat.login');
	}
  	public function event1(){
  
  		$redirect_uri=urlencode(env('APP_URL').'/wechat/code');
  		$url= 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect  ';
  		
  		header('Location:'.$url);
  	}
  	public function code(Request $request){
  			$code=$request->all();
  			$re=$code['code'];
  			// dd($code);
  			$access_token=file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_APPSECRET').'&code='.$re.'&grant_type=authorization_code');
  			// dd($access_token);
  			$token=json_decode($access_token,1);
  			// dd($token);
  			$usr=DB::table('usr')->where(['openid'=>$token['openid']])->first();
  			if (!empty($usr)) {
  				$request->session()->put('uid',$usr->uid);
  				dd('授权登陆成功');
  			}else{
  				//连接事务
  				  DB::connection('mysql')->beginTransaction();
  				  //添加用户表返回id
  				$user=DB::table('user')->insertGetId([
  						'name'=>rand(1000,9999).time(),
  						'pwd'=>'',
  						'type_time'=>time()
  				]);
  				// dd($user);
  				//存储openid表
  				$us=DB::table('usr')->insert(
  					[
  						'uid'=>$user,
  						'openid'=>$token['openid']
  					]
  				);

  				if ($user && $us) {
  					$request->session()->put('uid',$user);
  					dd('授权登陆成功');
  				}else{
  					dd('授权登陆失败');
  				}
  				// dd($us);
  			}
  	}
  	public  function list_add(){
  		return view('wechat.list_add');
  	}
  	//添加标签
  public function label_add(Request $request){
  	
  	$access_token=$this->wechat->access_token();
  	$url='https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$access_token;
  	// dd($url);
  	$data=[
  			  'tag' => 
  			[     
  				
  				'name' =>$request->all()['name'],
  			  ]
  		
  	];
  	// dd($data);
  	// 
  	$d=$this->wechat->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
  	return redirect('wechat/label_list');
  }
  //标签列表
  public function label_list(){

  		$data='https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$this->wechat->access_token();
  		// dd($data);
  		// 
  		$d=$this->wechat->curl_get($data);
  		// dd($d);
  		$do=json_decode($d,1);
  		// dd($do);
  		return view('wechat.label_list',['data'=>$do['tags']]);
  }
  public function label_del(Request $request){
  		$d=$request->all();
  		// dd($data);
  		$del='https://api.weixin.qq.com/cgi-bin/tags/delete?access_token='.$this->wechat->access_token();
  		$data=[
  			"tag"=>[       "id" => $d['id']   ]
  		];
  		$d=$this->wechat->curl_post($del,json_encode($data,JSON_UNESCAPED_UNICODE));
  		$res=json_decode($d,1);
  		// dd($res);
  		if ($res['errcode']==0) {
  			return redirect('wechat/label_list');
  		}else{
  			
  			dd('此为默认不能删除，请返回');
  		}
  }
  //修改页面
  public function label_update(Request $request){
  		$req=$request->all();
  		// dd($req);
  		return view('wechat.label_update',['data'=>$req]);
  }
  //修改执行页面
  public function do_update(Request $request){
  		$req=$request->all();
  		// dd($req);
  		$url='https://api.weixin.qq.com/cgi-bin/tags/update?access_token='.$this->wechat->access_token();
  		$data=[
  			 "tag" =>
  			  [    "id" =>$req['id'],     "name" =>$req['name']   ]
  		];
  		$d=$this->wechat->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
  		$res=json_decode($d,1);
  		// dd($res);
  		if ($res['errcode']==0) {
  			return redirect('wechat/label_list');
  		}else{
  			
  			dd('此为默认不能修改，请返回');
  		}
  }
  //为用户打标签
  public function label_index(Request $request){
  		$req=$request->all();
  		// dd($req);
  		$url="https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=".$this->wechat->access_token();
  		$data=[
  			   
			    "openid_list" => $req['openid_list'],  
			    "tagid" =>$req['id']
			  

  		];
  		$d=$this->wechat->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
  		$res=json_decode($d,1);
  		dd($res);
  }
  //获取用户所在的标签
  public function label_user(Request $request){
  		$req=$request->all();
  		// dd($req);
  		$url='https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token='.$this->wechat->access_token();
  		$data=[
  			  "openid" => $req['openid']
  		];
  		$d=$this->wechat->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
  		$res=json_decode($d,1);
  		// dd($res);
  		$requt=$this->wechat->label_lists()['tags'];

  		foreach ($res['tagid_list'] as $v) {
  			// dd($v);
  			foreach ($requt as $vo) {
  				// dd($vo);
  				if ($v == $vo['id']) {

  					echo $vo['name']."<br>";
  				}
  			}
  		}
  	
  }
  public function event(){
  		$info=file_get_contents("php://input");
  		//接受微信的xml数据存入日志
  		file_put_contents(storage_path('logs/wechat/'.date('Y-m-d').'.log'),"<<<<<<<<<<<<<<<<<<\n",FILE_APPEND);
  		file_put_contents(storage_path('logs/wechat/'.date('Y-m-d').'.log'),$info."\n",FILE_APPEND);
  		//解析xml
  		$xml_obj=simplexml_load_string($info,'SimpleXMLELement',LIBXML_NOCDATA);
  		$xml_arr=(array)$xml_obj;
  		dd($xml_arr);

  }
  public function label_xido(Request $request){
  		$req=$request->all();

  		return view('wechat.label_xido',['data'=>$req]);

  }

  //标签推送消息
 	public function label_xi(Request $request)
 	{
 		$req=$request->all();
 		// dd($req);
 		$url="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=".$this->wechat->access_token();
 		$data=[

		   "filter"=>[
		      "is_to_all"=>false,
		      "tag_id"=>$req['id']
		   ],
		   "text"=>[
		      "content"=>$req['content']
		   ],
		    "msgtype"=>"text"
		
 		];

 		$d=$this->wechat->curl_post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
  		$res=json_decode($d,1);
  		dd($res);
 	}

}
