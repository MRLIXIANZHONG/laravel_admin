<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 * Date: 2019/10/17
 */

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Validate\SiteValidate;
use Illuminate\Http\Request;

class IndexController extends BaseController {

    public function index(){
        return view('Admin.index');
    }

    /**
     * 用户微信绑定
     * Created by Lxd
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function wechatBind(Request $request)
    {
        $params = $request->all();
        $validate = SiteValidate::WechatBind($params, $msg);
        if ($validate !== true) {
            return response()->json($msg);
        }
        $execute = (new User())->wechatBind($params);
        return response()->json($execute);
    }
}
