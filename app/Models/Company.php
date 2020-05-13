<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends BaseModel
{
    use SoftDeletes;

    protected $table = 'companys';
    protected $primaryKey = "id";//关键字
    protected $guarded = [];
    protected $dates=['deleted_at'];//软删除字段,记录删除时间
    /**
     * model检索方法
     * Created by Lxd
     * @param $params
     * @return mixed
     */
    public function search($params)
    {
        $query = $this;
        if(!empty($params['name'])){
            $query = $query->where('name','like','%'.$params['name'].'%');
        }
        if (!empty($params['liaison'])) {
            $query = $query->where('liaison','like', '%'.$params['liaison'].'%');
        }
        if (!empty($params['liaison_phone'])) {
            $query = $query->where('liaison_phone','like', '%'.$params['liaison_phone'].'%');
        }
        //查询
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $re = $query->orderBy('id', 'desc')->paginate($limit);
        $appendData = $re->appends(array(
            'name' => @$params['name'],
            'liaison' => @$params['liaison'],
            'liaison_phone' => @$params['liaison_phone'],
            'limit' => @$params['limit'],
        ));
        return $re;
    }

    /**
     * 公司添加
     * Created by Lxd
     * @param $params
     * @return array
     */
    public function createData($params)
    {
        $this->name = $params['name'];
        $this->add = $params['add'];
        $this->liaison = $params['liaison'];
        $this->liaison_phone = $params['liaison_phone'];
        $this->remark = $params['remark'];
        if($this->save()){
            return ['code' =>200, 'infor' => '添加成功'];
        }
        return ['code' => 302, 'infor' => '网络异常,入库失败'];
    }

    /**
     * 公司修改
     * Created by Lxd
     * @param $params
     * @return array
     */
    public function updateData($params)
    {
        try {
            $info = $this->find($params['id']);
            $info->name = $params['name'];
            $info->add = $params['add'];
            $info->liaison = $params['liaison'];
            $info->liaison_phone = $params['liaison_phone'];
            $info->remark = $params['remark'];
            if (!$info->save()) throw new \Exception('添加失败');
        }catch (\Exception $e) {
            return ['code'=>456,'infor'=>$e->getMessage()];
        }
        return ['code'=>200,'infor'=>'修改公司信息成功'];
    }

    /**
     * ajax|分页获取
     * Created by Lxd
     * @param $params
     * @return array
     */
    public function getCompany($params)
    {
        // 分页参数
        $page = isset($params['page']) ? $params['page'] : 1;
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $offset = ((int)$page - 1) * (int)$limit;

        $query = $this;
        if(!empty($params['name'])){
            $query = $query->where('name','like','%'.$params['name'].'%');
        }
        $list = $query->orderBy('id', 'desc')
            ->select('id','name','liaison')
            ->offset($offset)->limit($limit)->get()->toArray();

        if (count($list)) {
            return ['code'=>200,'infor'=>$list];
        } else {
            return $page == 1 ? ['code'=>404,'infor'=>false] : ['code'=>404,'infor'=>'已无数据'];
        }
    }
}