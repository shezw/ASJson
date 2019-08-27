<?php
/**
 * ASJson static function
 * PHP Version 7.0
 *
 * @see       https://github.com/shezw/ASJson/ The ASJson GitHub project
 * @author    Sprite <hello@shezw.com>
 * @copyright 2018 - 2019 Sprite
 * @license   
 */

/**
 * [ASJsonEncode 为变量进行ASJson编码 Encode variant to ASJson String]
 * @Author   Sprite                   hello@shezw.com http://donsee.cn
 * @DateTime 2019-08-23T15:29:20+0800
 * @version  1.0
 * @param    any               $value                 [变量]
 * @param    bool              $sub                   [是否子变量]
 * @return   string(ASJson)                           [结果]
 */
function ASJsonEncode( $value , bool $sub = false ){

    $VALIDTYPES  = ['integer'=>'i','int'=>'i','double'=>'d','bool'=>'b','boolean'=>'b','null'=>'n','NULL'=>'n','string'=>'s'];

    $encode = [];
    $encode['_T']   = $VALIDTYPES[gettype($value)] ?? "ASJ";
    $encode['_V']   = [];

    if($encode['_T']=='ASJ'){

        foreach ($value as $k => $v) {

            $encode['_V'][$k] = ASJsonEncode($v,true);       

        }
    }else{
        $encode['_V'] = $value;
    }

    return $sub ? $encode : json_encode($encode);
}

/**
 * [ASJsonDecode 为ASJson解码 Decode ASJson to variant]
 * @Author   Sprite                   hello@shezw.com http://donsee.cn
 * @DateTime 2019-08-27T22:42:58+0800
 * @version  1.0
 * @param    string(ASJson)        $ASJson         [ASJson字符串]
 * @return   any
 */
 function ASJsonDecode( $ASJson ){

    $decode = gettype($ASJson)=='array' ? $ASJson : json_decode($ASJson,true);

    if(!isset($decode['_T'])){

        foreach ($decode as $key => $value) {
            $decode[$key] = ASJsonDecode($value);
        }

        return $decode;
    }

    switch ($decode['_T']) {
        case 'ASJ':
        $v = ASJsonDecode($decode['_V']);
        break;
        case 'i':
        $v = (int)$decode['_V'];
        break;
        case 'f':
        case 'd':
        $v = (double)$decode['_V'];
        break;
        case 'b':
        $v = ['true'=>true,'false'=>false,'TRUE'=>true,'FALSE'=>false,'0'=>false,'1'=>true,' '=>false,'NULL'=>false][$decode['_V']] ?? false;
        break;
        case 'n':
        $v = NULL;
        break;
        case 's':
        default:
        $v = $decode['_V'];
        break;
    }

    return $v;

}

?>
