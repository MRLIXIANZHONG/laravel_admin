<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Navigation extends BaseModel
{
    protected $table = 'navigations';
    protected $primaryKey = "id";//关键字
    public $timestamps = false;
    protected $guarded = [];

    /**
     * 导航信息获取
     * Created by Lxd
     * @param $id
     * @return mixed
     */
    public function getNavigationById($id){
        return $this->find($id);
    }

    /**
     * 导航检索
     * Created by Lxd
     * @param $params
     * @return mixed
     */
    public function search($params){
        $query = $this->from('navigations as n');
        if(!empty($params['url'])){
            $query = $query->where('p.url','like','%'.$params['p.url'].'%');
        }
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $re = $query->leftJoin('permissions as p','p.id','=','n.permission_id')
            ->select('n.*','p.name as permissionName','p.display_name')
            ->orderBy('n.parent_id','asc')
            ->orderBy('n.sequence', 'asc')
            ->orderBy('n.id', 'asc')->paginate($limit);
        $appendData = $re->appends(array(
            'url' => @$params['url'],
        ));
        return $re;
    }

    /**
     * 获取所有导航
     * Created by Lxd
     * @return mixed
     */
    public function searchAll(){
        return $this->from('navigations as n')
            ->leftJoin('permissions as p','p.id','=','n.permission_id')
            ->select('n.*','p.name as permissionName','p.display_name')
            ->orderBy('n.parent_id','asc')
            ->orderBy('n.sequence', 'asc')
            ->orderBy('n.id', 'asc')
            ->get()->toArray();
    }

    /**
     * 添加导航
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function Create($params){
        $validatorhelp = $this->Validator_NavigationModel($params,'insert');
        if($validatorhelp !== true){
            return response()->json(['code' => 456, 'infor' => $validatorhelp,]);
        }
        $params['created_at'] = getDateTime();
        if($this->insert($params)){
            $this->redisOperation(BaseModel::NAVIGATIONREDIS,'set');
            return response()->json(['code' =>200, 'infor' => '导航添加成功']);
        }
        return response()->json(['code' => 302, 'infor' => '网络异常,入库失败',]);
    }

    /**
     * 修改导航
     * Created by Lxd
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function NavigationUpdate($params){
        $validatorhelp = $this->Validator_NavigationModel($params,'update');
        if($validatorhelp !== true){
            return response()->json(['code' => 456, 'infor' => $validatorhelp,]);
        }
        $params['updated_at'] = getDateTime();
        if($this->where('id',$params['id'])->update($params)){
            $this->redisOperation(BaseModel::NAVIGATIONREDIS,'set');
            return response()->json(['code' =>200, 'infor' => '导航修改成功']);
        }
        return response()->json(['code' => 302, 'infor' => '网络异常,入库失败',]);
    }

    /**
     * 导航删除
     * Created by Lxd
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function NavigationDelete($id){
        $idArr = explode(',',$id);
        foreach ($idArr as $v){
            $this->where('id',$v)->delete();
            $this->where('parent_id',$v)->delete();
        }
        $this->redisOperation(BaseModel::NAVIGATIONREDIS,'set');
        $infor = count($idArr) > 1 ? "批量删除导航成功" : "删除导航成功";
        return response()->json(['code' => 200, 'infor' => $infor]);
    }

    /**
     * 导航表单
     * Created by Lxd
     * @param $parms
     * @param string $type
     * @return bool|string
     * @throws \DfaFilter\Exceptions\PdsBusinessException
     */
    public function Validator_NavigationModel($parms,$type = 'insert'){
        foreach ($parms as $v){
            if(!is_string($v)) continue;
            if($this->Validator_SensitiveHelper($v,'detect'))
                return '表单含有敏感词内容,请重新输入';
        }
        $rules = [];
        $message = [];
        foreach ($parms as $k=>$v){
            switch ($k){
                case 'name':
                    if($type == 'insert'){
                        $rules['name'] = 'required|unique:navigations,name';
                    }else{
                        $rules['name'] = [
                            'required',
                            Rule::unique('navigations')->ignore($parms['id'],'id'),
                        ];
                    }
                    $message['name.required'] = '请输入导航名称';
                    $message['name.unique'] = '该导航名已存在';break;
                case 'sequence':
                    $rules['sequence'] = 'required|integer|max:9999';
                    $message['sequence.required'] = '请输入排序';
                    $message['sequence.integer'] = '排序请输入数字';
                    $message['sequence.max'] = '排序最大不能超过9999';break;
            }
        }
        $validator = Validator::make($parms, $rules, $message);
        return $validator->passes() ? true : $validator->errors()->first();
    }
}
