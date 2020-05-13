<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Iprecording extends BaseModel{
    use SoftDeletes;
    protected $table = 'iprecordings';
    protected $primaryKey="id";//关键字
    public $timestamps=false;
    protected $guarded=[];
    protected $dates=['deleted_at'];//软删除字段,记录删除时间
    protected $appends = ['iptype_text'];

    const IPTYPEACTIVITY = 1;
    const IPTYPEDISABLE = 2;
    /**
     * 是否限制
     * @var array
     */
    public $typeLabel = [self::IPTYPEACTIVITY => '不限制', self::IPTYPEDISABLE => '限制',];
    public function getIptypeTextAttribute(){
        return isset($this->typeLabel[$this->iptype]) ? $this->typeLabel[$this->iptype] : $this->iptype;
    }

    /**
     * model检索
     * Created by Lxd
     * @param $params
     * @return mixed
     */
    public function search($params)
    {
        $query = $this;
        if (!empty($params['address'])) {
            $query = $query->where('address','like','%'.$params['address'].'%');
        }
        if (!empty($params['orderAccessnum'])){
            $query = $query->orderBy('accessnum',$params['orderAccessnum']);
        }
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $list = $query->orderBy('id','desc')->paginate($limit);
        $list = $list->appends([
            'address'=>@$params['address'],
            'orderAccessnum'=>@$params['orderAccessnum'],
            'limit'=>$limit,
        ]);
        return $list;
    }

    /**
     * @param $ip
     * @param $path
     * @param $permission
     * @return int
     * 访问IP记录
     *
     */
    public function user_ip_routepath_up($ip,$path,$permission){
        try {
            $data = $this->where('address', $ip)->first();
            if ($data) {
                $accessnum = $data->accessnum + 1;
                $this->where('address', $ip)->update(['atlastroute' => $path, 'created_at' => getDateTime(), 'accesspermission'=>$permission,'accessnum'=>$accessnum]);
                return $data->iptype;
            } else {
                $data->address = $ip;
                $data->created_at = getDateTime();
                $data->atlastroute = $path;
                $data->accesspermission = $permission;
                return $this->save();
            }
        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * 修改用户状态
     * Created by Lxd
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function ipRecordEditType($id){
        $idArr = explode(',',$id);
        foreach ($idArr as $v){
            try {
                $info = $this->find($v);
                $newType = $info->iptype == self::IPTYPEACTIVITY ? self::IPTYPEDISABLE : self::IPTYPEACTIVITY;
                $info->iptype = $newType;
                $info->save();
            }catch (\Exception $e){
                continue;
            }
        }
        $infor = count($idArr) > 1 ? "批量修改用户状态成功" : "修改用户状态成功";
        return response()->json(['code' => 200, 'infor' => $infor]);
    }

}
