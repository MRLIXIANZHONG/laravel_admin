<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use DfaFilter\SensitiveHelper;

class BaseModel extends Eloquent{

    const SENSITIVE_HELPER = 'JkmRedis_SensitiveHelper';
    const NAVIGATIONREDIS = 'JkmRedis_navigation';
    public $redisArr = [
        self::NAVIGATIONREDIS => '系统导航'
    ];

//    public function __construct(array $attributes = [])
//    {
//        parent::__construct($attributes);
//    }

    /**
     * 获取所有路由数组[web端的]
     * Created by Lxd
     * @return array
     */
    public function getAllRoutes(){
        $routes = Route::getRoutes();
        foreach($routes as $k=>$value) {
            if(in_array('web',$value->action['middleware'])) {
                $path[] = $value->uri;
            }
        }
        return $path;
    }

    /**
     * 系统redis重置
     * Created by Lxd
     * @return \Illuminate\Http\JsonResponse
     */
    public function redisRenew(){
        $arr = $this->redisArr;
        $eror = [];
        foreach($arr as $key => $value){
            $up = $this->redisOperation($key,'set');
            if(!$up){                                  //redis重置失败,截停!
                $eror[] = $key;
            }
        }
        if(empty($eror)){
            return response()->json(['code'=>200,'infor'=>'循环更新成功']);
        }else{
            $eror = implode(',',$eror);
            return response()->json(['code'=>302,'infor'=>"{$eror}|更新失败,请联系运维解决!"]);
        }
    }

    /**
     * Created by Lxd
     * @param $SensitiveWord
     * @param string $FilterType
     * @return mixed
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     * 敏感词过滤基于php-dfa-sensitive
     * 地址:https://github.com/FireLustre/php-dfa-sensitive
     * 敏感词库一次生成,存入REDIS,单次请求速度目前在可控范围内
     */
    public function Validator_SensitiveHelper($SensitiveWord,$FilterType = 'replace'){
        if(!Redis::exists(self::SENSITIVE_HELPER)){
            $wordFilePath = base_path().'/public/Sensitiveword/Sensitiveword.txt';
            $handle = SensitiveHelper::init()->setTreeByFile($wordFilePath);    //TODO 读取本地敏感词库
            Redis::set(self::SENSITIVE_HELPER,serialize($handle));
        }
        $handle = unserialize(Redis::get(self::SENSITIVE_HELPER));
        switch ($FilterType){
            case 'replace'  :   return $handle->replace($SensitiveWord, '*', true);break;
            case 'detect'   :   return $handle->islegal($SensitiveWord);break;
            case 'mark'     :   return $handle->mark($SensitiveWord, '<mark>', '</mark>');break;
            case 'obtain'   :   return $handle->getBadWord($SensitiveWord);break;
        }
        echo '请指定敏感词处理参数';exit;
    }

    public function hasMultistageShow($submenu){
        if(!is_array($submenu))
            return false;
        foreach ($submenu as $v){
            if(getAuth()->can($v['permissionName']))
                return true;
        }
        return false;
    }
    
    /**
     * redis获取/重置
     * Created by Lxd
     * @param $key
     * @param string $pattern
     * @return array|mixed
     */
    public function redisOperation($key,$pattern = 'get'){
        switch ($key){
            case self::NAVIGATIONREDIS: //导航操作
                switch ($pattern){
                    case 'get':
                        if(!Redis::exists(self::NAVIGATIONREDIS)){              //生成导航redis
                            $data = $this->redisOperation(self::NAVIGATIONREDIS,'set');
                        }else {
                            $data = unserialize(Redis::get(self::NAVIGATIONREDIS));
                        }
                        break;
                    case 'set':
                        $allNavigation = (new Navigation())->searchAll();       //数据库数据获取
                        $data = [];
                        foreach ($allNavigation as $key=>$value){
                            if(empty($value['parent_id'])){                     //顶级导航
                                $data[$value['id']] = $value;
                            }else{
                                $data[$value['parent_id']]['submenu'][] = $value;
                            }
                        }
                        Redis::set(self::NAVIGATIONREDIS,serialize($data));     //存入或覆盖
                        break;
                }
        }
        return $data;
    }
}
