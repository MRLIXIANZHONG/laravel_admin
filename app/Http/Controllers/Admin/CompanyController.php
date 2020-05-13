<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */
namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Validate\CompanyValidate;
use Illuminate\Http\Request;

class CompanyController extends BaseController{

    /**
     * 公司列表页
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $Model = new Company();
        $params = $request->input();
        $re = $Model->search($params);
        return view("Admin.company.index",compact("re"));
    }

    /**
     * 添加
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit');
            $validate = CompanyValidate::dataCreate($params, $msg);
            if ($validate !== true) {
                return response()->json($msg);
            }
            $execute = (new Company())->createData($params);
            return response()->json($execute);
        }
        return view('Admin.company.create');
    }

    /**
     * 修改
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function update(Request $request)
    {
        if($request->isMethod('put')){
            $params = $request->except('_method','_token','nm_input_text','nm_submit','role');
            $validate = CompanyValidate::dataUpdate($params, $msg);
            if ($validate !== true) {
                return response()->json($msg);
            }

            $execute = (new Company)->updateData($params);
            return response()->json($execute);
        }
        $info = Company::find($request->get('id'));
        if($info)
            return view('Admin.company.update',compact('info'));
        else
            return redirect('Custom_throw?error=信息获取失败');
    }

    /**
     * 删除
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $params = $request->all();
        $validate = CompanyValidate::dataDelete($params, $msg);
        if (true !== $validate) {
            return response()->json($msg);
        }
        $del = Company::where('id',$params['id'])->delete();
        if($del)
            return response()->json(['code' => 200, 'infor' => '删除成功']);
        else
            return response()->json(['code' => 456, 'infor' => '网络异常,删除失败']);
    }

    /**
     * ajax|分页获取
     * Created by Lxd
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompany(Request $request)
    {
        $params = $request->all();
        $list = (new Company())->getCompany($params);
        return response()->json($list);
    }
}