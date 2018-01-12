<?php
/*$str = 'eyJhcHBpZCI6IjEwMDAyMiIsInBheWlkIjoiNzgzIiwibW9uZXkiOjIsIm91dF9vcmRlcmlkIjoiMTUwMzY0NzcyNiJ9';

echo base64_decode($str);
echo var_dump(json_decode(base64_decode($str)));exit;
echo $str;
echo "\r\n";
echo urlencode($str);
echo "\r\n";
echo urldecode(urlencode($str));
echo "\r\n";
echo (str_replace(' ','+',urldecode($str)));

echo "\r\n";
echo _encrypt((str_replace(' ','+',urldecode($str))),'DECODE');
exit;


		
		
$str = '303dbLAGbxfZXdo76eJ+lZQDvpEpOPc0pluRXSep/JrIB/3qvo1yqClpX33ueDoqg+/37Zhd0xOFq/WgEIKNBsopEGAaOXBS5n/rTWwg6Q8678AM';
echo (urldecode($str));
echo '=====';
echo _encrypt(base64_decode(urldecode($str)),'DECODE');
exit;

var_dump(isUrl('http://localhost:15955/Pay/Return/ltmall-Plugin-Payment-Dypay'));exit; */
$rsa2 = new Rsa2();
header("Content-type:text/html;charset=utf-8");

$url="http://api.bxwyx.com.cn/api/sendSms";

$post_data['appKey']='4905cdcb-64df-43c3-812c-a3f9bc37ad0e';
$post_data['phone']='13328686355';
$post_data['content']='test' . date('Y-m-d H:i:s');
$post_data['timestamp']=round(microtime(true)*1000);
ksort($post_data);
$query = urldecode(http_build_query($post_data));
$post_data['sign'] = urlencode($rsa2->createSign($query));      //生成签名
print_r($post_data);
$result = getCurl($url,$post_data);
var_dump($result);

function getCurl($url,$post_data=null,$Referer=null){//get https的内容
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);//不输出内容
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_TIMEOUT,15);
	curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0');
	if($post_data!=null){
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
	}
	if($Referer!=null){
		curl_setopt($ch,CURLOPT_REFERER,$Referer);
	}
	$result= curl_exec($ch);
	curl_close($ch);
	return $result;     
}


/**
 * Created by PhpStorm.
 * User: webff
 * Date: 2017/5/12
 * Time: 20:03
 */
class Rsa2
{
    private static $PRIVATE_KEY ="-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQChQRiLQW59uYwDOzCzZhfVG9PWttV4vhuPLyhft8eUfif9SDsa
YCfwvNyo5ZvZByob2NJXsiBClHGJKvMmknAxyUg+M87BQ7NAMRnsR32PNINnl91S
pUxD6m4MfJy9rD8s2reh8nUPip0N0eku90bcjuJ/B+lekYspSm+0vFRQAQIDAQAB
AoGBAJHQiV8zNbSemgs59Nnkkdx1I2PfRL3AOq+JVxrm1qRiR8rzC+7X0IYSYf6g
UgM4RU2VDO+b9Sv/i4MbwW+5r5UFpsnH1692ePFJjFPPpclEz0Lywu8LXj3d2BVH
nTSAFb3MW1ubhMwH1eNe/UsEbAfedP/0Zp+bXDQEd/0pmpKhAkEAwXD7JHIpxUog
Wlqc4LtT22F4SqljN1mnmNK+jRefHHEpP6wLOE6eNFoKslMxTTiqfiYLBg/BbZps
wVH5zdTBVQJBANVnWNZ0d0sW9ERpUq8/KA/gBjjq59NI3yeDxiGMwZyBAE4Ng/vr
9F789QUPOMKoEMrLecOHtdu9dFlN93iYQ/0CQFH5zCQ00SjPcgh4T/UYzb+xfaW+
RNKHBvRHkwL92KrX9dAK27Jf9sFeyxupD8KW2gGdo5xM/v6wq5f9Ymxs1gUCQQCn
Q0yPMlP6J/Cm2kJzlzXoU3etDvlLUneN+ivGShPKfhXmF5PWVdeAyBWntImRkLcw
rBExH2J769+Qy5pnDDllAkEAj77/IK/IB3E+Yj5Ve8xOQV3sIxdxJDMW56LkGACN
4pgXGbzBg0MBf1TDsGlau1fyPyelSc5p99Yc7jqzWcpdhA==
-----END RSA PRIVATE KEY-----";
    private static $PUBLIC_KEY  = '----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQChQRiLQW59uYwDOzCzZhfVG9PW
ttV4vhuPLyhft8eUfif9SDsaYCfwvNyo5ZvZByob2NJXsiBClHGJKvMmknAxyUg+
M87BQ7NAMRnsR32PNINnl91SpUxD6m4MfJy9rD8s2reh8nUPip0N0eku90bcjuJ/
B+lekYspSm+0vFRQAQIDAQAB
-----END PUBLIC KEY-----';



    /**
     * 获取私钥
     * @return bool|resource
     */
    private static function getPrivateKey()
    {
        $privKey = self::$PRIVATE_KEY;
        return openssl_pkey_get_private($privKey);
    }

    /**
     * 获取公钥
     * @return bool|resource
     */
    private static function getPublicKey()
    {
     
        $publicKey = self::$PUBLIC_KEY;
        return openssl_pkey_get_public($publicKey);
    }

    /**
     * 创建签名
     * @param string $data 数据
     * @return null|string
     */
    public function createSign($data = '')
    {
        if (!is_string($data)) {
            return null;
        }
        return openssl_sign($data, $sign, self::getPrivateKey(),OPENSSL_ALGO_SHA1 ) ? base64_encode($sign) : null;
    }

    /**
     * 验证签名
     * @param string $data 数据
     * @param string $sign 签名
     * @return bool
     */
    public function verifySign($data = '', $sign = '')
    {
        if (!is_string($sign) || !is_string($sign)) {
            return false;
        }
        return (bool)openssl_verify(
            $data,
            base64_decode($sign),
            self::getPublicKey(),
            OPENSSL_ALGO_SHA1
        );
    }
}

	
?>