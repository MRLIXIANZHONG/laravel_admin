<?php
/**
 * Created by Lxd.
 * QQ: 790125098
 */

namespace App\Models;

class UploadFile extends BaseModel
{
    /**
     * 文件夹创建
     * Created by Lxd
     * @param $path
     * @return array|bool
     */
    public function mkdirInLocal($path){
        if (!is_dir($path)) {
            @mkdir($path, 0777,true);
        }
        if (!is_writeable($path)) {
            return ['code'=>456, 'msg'=>'上传目录不可写', 'data'=>[]];
        }
        return true;
    }

    /**
     * Laravel文件上传
     * @param object $file File对象
     * @param string $dir 目录
     * @param array $exceptExtends 仅允许的文件可上传
     * @return array 返回出来结果
     */
    public function fileUpload($file, $dir = '', $exceptExtends = ["png", "jpg", "gif"])
    {
        if ($file) {
            $fileName = sha1(uniqid(null, true));
            $fileExtends = $file->getClientOriginalExtension();
            $uploadDir = ($dir != '') ? public_path($dir) : public_path();
            if(!$mkdir = $this->mkdirInLocal($uploadDir)){return $mkdir;}
            $url = trim(str_replace('\\','/',trim(trim($dir,'/'),'\\').'\\'.$fileName.'.'.$fileExtends),'/');
            if (!in_array($fileExtends, $exceptExtends)) {
                return ['code'=>456, 'msg'=>'该文件类型禁止上传', 'data'=>[]];
            }
            if ($file->move($uploadDir, $fileName.'.'.$fileExtends)) {
                return ['code'=>200, 'msg'=>'上传成功', 'data'=>$url];
            } else {
                return ['code'=>302, 'msg'=>'上传失败', 'data'=>[]];
            }
        } else {
            return ['code'=>404, 'msg'=>'请上传文件', 'data'=>[]];
        }
    }

    /**
     * 图片上传|压缩尺寸
     * Created by Lxd
     * @param $file
     * @param null $width
     * @param null $height
     * @param int $ziptype
     * @return array|bool|string
     */
    function UpdateImage($file,$width = null,$height = null,$ziptype = 1){
        try {
            $allowed_extensions = ["png", "jpg", "gif"];
            if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
                return ['code'=>456, 'msg'=>'图片上传失败，只能上传PNG\JPG\GIF图片！', 'data'=>[]];
            }
            $destinationPath = 'uploads/img/';          //存放路径
            if(!$mkdir = $this->mkdirInLocal($destinationPath)){return $mkdir;}
            $extension = $file->getClientOriginalExtension();   //获取后缀
            $fileName = sha1(uniqid(null, true)) . '.' . $extension;
            $file->move($destinationPath, $fileName);
            $width = $width ?: 500;
            $height = $height ?: 300;
            $caiimage = $this->ModifyImage($width, $height, $destinationPath, $fileName, $ziptype, 2);//压缩图片
            if ($caiimage == 88 or $caiimage == 99) {
                return ['code'=>456, 'msg'=>'图片处理失败，请上传正确的图片文件！', 'data'=>[]];
            }
            if($ziptype === 2) {
                $filepath = $destinationPath . $fileName;
            }else{
                $filepath = $destinationPath .'minipicture'. $fileName;
            }
            return ['code'=>200, 'msg'=>'上传成功', 'data'=>$filepath];
        }catch (\Exception $e){
            return ['code'=>302, 'msg'=>'未获取到图片!/请检查文件大小是否符合', 'data'=>[]];
        }
    }

    /**
     * Base64 图片上传
     * Created by Lxd
     * @param $picBase64Info
     * @return array|bool
     */
    function UpdateBase64Image($picBase64Info){
        try {
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $picBase64Info, $result)) {
                $type = $result[2];
                if (in_array($type, array('pjpeg', 'jpeg', 'jpg', 'gif', 'bmp', 'png'))) {
                    $destinationPath = 'uploads/img/';
                    if(!$mkdir = $this->mkdirInLocal($destinationPath)){return $mkdir;}
                    $fileName = sha1(uniqid(null, true)) . '.' . $type;
                    if (file_put_contents($destinationPath . $fileName, base64_decode(str_replace($result[1], '', $picBase64Info)))) {
                        return ['code'=>200, 'msg'=>'上传成功', 'data'=>$destinationPath . $fileName];
                    } else {
                        return ['code'=>456, 'msg'=>'文件写入失败!(文件写入失败)', 'data'=>[]];
                    }
                } else {
                    return ['code'=>456, 'msg'=>'尚不支持该类文件上传!(仅支持pjpeg/jpeg/jpg/gif/bmp/png)', 'data'=>[]];
                }
            } else {
                return ['code'=>456, 'msg'=>'文件错误(base64字符串非法)!', 'data'=>[]];
            }
        }catch (\Exception $e){
            return ['code'=>302, 'msg'=>'未知异常导致base64文件写入失败!', 'data'=>[]];
        }
    }

    /**
     * 图片处理类/尺寸修改
     * @param $width1
     * @param $high1
     * @param $destinationPath|文件夹路径
     * @param $fileName       |文件名
     * @param int $n          |1=保留原图 2=覆盖原图
     * @param int $type       |1=比例缩小 2=尺寸裁剪
     * @return int
     */
    function ModifyImage($width1, $high1, $destinationPath, $fileName, $n = 2, $type = 1)
    {
        try {
            //---ImageCopyResampled参数说明:https://blog.csdn.net/veryhapp/article/details/24888525---//了解一下
            $arrimg = getimagesize($destinationPath . $fileName);               //获取文件属性
            if ($type == 1) {
                if ($arrimg['0'] > $arrimg['1']) {
                    $width = $width1;
                    $high = $width1 / $arrimg['0'] * $arrimg['1'];              //等比例缩小高
                } else {
                    $high = $high1;
                    $width = $high1 / $arrimg['1'] * $arrimg['0'];              //等比例缩小宽
                }
            } else {
                $width = $width1;
                $high = $high1;
            }
            $thumb = @imagecreatetruecolor($width, $high);                       //创建画布大小
            if ($arrimg['2'] == 1) {                                            //判断图片后缀 1-gif 2-jpg 3-png
                $source = @imagecreatefromgif($destinationPath . $fileName);
            } elseif ($arrimg['2'] == 2) {
                $source = @imagecreatefromjpeg($destinationPath . $fileName);
            } elseif ($arrimg['2'] == 3) {
                $source = @imagecreatefrompng($destinationPath . $fileName);
            } else {
                return 88;                                                      //只写了支持gif jpg png三种格式的图片
            }
            if($source == false){
                return 99;
            }
            $icr = @ImageCopyResampled($thumb, $source, 0, 0, 0, 0, $width, $high, $arrimg[0], $arrimg[1]);
            if ($n == 1) {                                                      //缩略图生成,源文件保留
                imagejpeg($thumb, $destinationPath . "minipicture" . $fileName);
            } else {
                imagejpeg($thumb, $destinationPath . $fileName);
            }
            return 66;
        } catch (\Exception $e) {
            return 99;
        }
    }

    /**
     * 图片处理类/增加水印/该方法暂未使用
     * @param $destinationPath  |文件夹路径
     * @param $fileName         |文件名
     * @param $n                |1=加图片水印 2=加文字水印
     * @return int
     */
    function AddWaterMark($destinationPath, $fileName, $n)
    {
        try {
            $imgsize = getimagesize($destinationPath . $fileName);              //获取图片属性
            if ($imgsize['2'] == 1) {
                $img = imagecreatefromgif($destinationPath . $fileName);
            } elseif ($imgsize['2'] == 2) {
                $img = imagecreatefromjpeg($destinationPath . $fileName);
            } elseif ($imgsize['2'] == 3) {
                $img = imagecreatefrompng($destinationPath . $fileName);
            } else {
                return 99;                                                      //只写了支持gif jpg png三种格式的图片
            }
            if ($n == 1) {
                $logopath = $destinationPath . "y.jpg";                         //水印路径
                $logo = imagecreatefromjpeg($logopath);                         //请注意水印图片的格式选择图片处理函数
                //$logo = imagecreatefromgif($logopath);
                //$logo = imagecreatefrompng($logopath);
                $logosize = getimagesize($logopath);
                $alpha = 80;                                                    //清晰百分比
                //-----------合并水印图片 这个水印显示位置得看一下图片坐标系的文档了解一下--------------//
                imagecopymerge($img, $logo, $imgsize[0] - $logosize[0], $imgsize[1] - $logosize[1], 0, 0, $logosize[0], $logosize[1], $alpha);//右下角
                // print_r($imgsize[0]-$logosize[0]);          //738(938-200)
                // echo "</br>";
                // print_r($imgsize[1]-$logosize[1]);          //466(666-200)
                // echo "</br>";
                // print_r($logosize[0]);                      //200
                // echo "</br>";
                // print_r($logosize[1]);                      //200
                // die();
                // print_r($imgsize);
                // echo "</br>";
                // print_r($logosize);
                // die();
                imagejpeg($img, $destinationPath . "shui-image" . $fileName);
            } else {
                $dst = imagecreatefromstring(file_get_contents($destinationPath . $fileName));    //文件字符串处理
                $font = '/simsun.ttc';                                          //字体设置，引入public文件夹下的字体文件
                $black = imagecolorallocate($dst, 255, 0, 0);                   //字体颜色
                imagefttext($dst, 50, 0, 50, 50, $black, $font, '如果感到快乐你就拍拍手');
                imagejpeg($dst, $destinationPath . "shui-text" . $fileName);
            }
        } catch (\Exception $e) {
            return 99;
        }
    }
}
