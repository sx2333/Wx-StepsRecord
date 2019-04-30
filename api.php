<?php
define("TOKEN","weixin");
$wechatobj=new wechatCallbackapiTest();
//(1)先验证
//$wechatobj->valid();
//(2)验证成功后注释掉valid(),开启自动回复功能
$wechatobj->responseMsg("欢迎关注");
class wechatCallbackapiTest
{
    public function valid(){
		//接受随机字符串
        $echoStr=$_GET["echostr"];
        //进行用户数字签名验证
        if($this->checkSignature()){
			//如果成功,则返回随机字符串
            echo $echoStr;
            exit;
        }
    }
    //定义自动回复功能
    public function responseMsg(){
        //接收用户端发送的XML数据
        $postStr=$GLOBALS["HTTP_RAW_POST_DATA"];
        //判断XML数据是否为空
        if(!empty($postStr)){
            libxml_disable_entity_loader(true);
            //通过simplexml进行xml解析
            $postObj=simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
           //微信手机端
            $fromUsername=$postObj->FromUserName;
           //微信公众平台
            $toUsername=$postObj->ToUserName;
            //接收用户发送关键词
            $keyword=trim($postObj->Content);
            $Event = $postObj->Event;
            $EventKey = $postObj->EventKey;
            //接受用户消息类型
            $msgType = $postObj->MsgType;
            //定义$longitude与$latitude
            $latitude = $postObj->Location_X;
            $longitude = $postObj->Location_Y;
            //时间戳
            $time=time();
            
            
            //文本发送模版
            $textTpl="<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[%s]]></MsgType>
                      <Content><![CDATA[%s]]></Content>
                      <FuncFlag>0</FuncFlag>
                      </xml>";      
            //订阅事件
            if($Event == "subscribe")
            {
                $msgType = "text";
                $contentStr ="使用指导";
                $resultStr=sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                echo $resultStr;
            }
            //音乐发送模版
            $musicTpl = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[%s]]></MsgType>
                      <Music>
                      <Title><![CDATA[%s]]></Title>
                      <Description><![CDATA[%s]]></Description>
                      <MusicUrl><![CDATA[%s]]></MusicUrl>
                      <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                      </Music>
                      </xml>";
            //图文发送模版
            $newsTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <ArticleCount>%s</ArticleCount>
                        %s
                        </xml>";
            //地理位置接口
            $locationTpl = "<xml>
                        <ToUserName>< ![CDATA[toUser] ]></ToUserName>
                        <FromUserName>< ![CDATA[fromUser] ]></FromUserName>
                        <CreateTime>1351776360</CreateTime>
                        <MsgType>< ![CDATA[location] ]></MsgType>
                        <Location_X>23.134521</Location_X>
                        <Location_Y>113.358803</Location_Y>
                        <Scale>20</Scale>
                        <Label>< ![CDATA[位置信息] ]></Label>
                        <MsgId>1234567890123456</MsgId>
                        </xml>";
            
            if($Event == 'click' and $EventKey =='V1001_TODAY_MUSIC')
            { 
                $msgType = "text";
                $contentStr= "您发送了一个信息";
                $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                echo $resultStr;
            }
            
            
            if($msgType=='text')
                //判断用户发送关键词是否为空
                if(!empty($keyword)){
                   if($keyword=='文本'){
                       //回复类型,如果为text,代表文本类型
                       $msgType="text";
                       //回复内容
                       $contentStr="您发送的是文本消息";
                       //格式化字符串
                       $resultStr=sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                       //把XML数据返回给手机端
                       echo $resultStr;
                   } elseif($keyword=='?'||$keyword=='？'){
                       //定义回复类型
                       $msgType=='text';
                       //回复内容
                       $contentStr = "[1]特种服务号码\n[2]通讯服务号码\n[3]银行服务号码\n您可以输入数字编号获取内容";
                       //格式化字符串
                       $resultStr=sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                       //返回数据到微信客户端
                       echo $resultStr;
                   } elseif ($keyword=='1'){
                       //定义回复类型
                       $msgType=='text';
                       //回复内容
                       $contentStr = "常用特种服务号码:\n匪警110\n火警：119";
                       //格式化字符串
                       $resultStr=sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                       //返回数据到微信客户端
                       echo $resultStr;
                   } elseif ($keyword=='2'){
                       //定义回复类型
                       $msgType=='text';
                       //回复内容
                       $contentStr = "常用通讯服务号码:\n中移动10086\n中电信：10000";
                       //格式化字符串
                       $resultStr=sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                       //返回数据到微信客户端
                       echo $resultStr;
                   }elseif ($keyword=='3'){
                       //定义回复类型
                       $msgType=='text';
                       //回复内容
                       $contentStr = "常用银行服务号码:\n工商银行95588\n建设银行：95533";
                       //格式化字符串
                       $resultStr=sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                       //返回数据到微信客户端
                       echo $resultStr;
                   } elseif ($keyword=='音乐'){
                       //定义回复类型
                       $msgType='music';
                       //定义音乐标题
                       $title = '英文歌曲';
                       //定义音乐类型
                       $desc = '英文歌曲原声大碟...';
                       //定义音乐链接
                       $url = 'http://374495727.duapp.com/music.mp3';
                       //定义高清音乐链接
                       $hqurl ='http://374495727.duapp.com/music.mp3';
                       $resultStr = sprintf($musicTpl,$fromUsername,$toUsername,$time,$msgType,$title,$desc,$url,$hqurl);
                       //返回xml数据到微信客户端
                       echo $resultStr;
                   }   elseif ($keyword=='图文'){
                       //设置回复类型
                       $msgType='news';
                       //设置要返回的图文数量
                       $count = 1;
                       //设置要回复的图文数据
                       $str = '<Articles>';
                       for($i=1;$i<=$count;$i++){
                           $str .= "<item>
                           <Title><![CDATA[运动小技巧]]></Title>
                           <Description><![CDATA[11个最佳跑步技巧！]]></Description>
                           <PicUrl><![CDATA[http://374495727.duapp.com/images/{$i}.jpg]]></PicUrl>
                           <Url><![CDATA[https://jingyan.baidu.com/article/3052f5a1a5af2897f31f869a.html]]></Url>
                           </item>";  
                       }
                       $str .= '</Articles>';
                       //格式化字符串
                       $resultStr = sprintf($newsTpl,$fromUsername,$toUsername,$time,$msgType,$count,$str);
                       //返回xml到微信客户端
                       echo $resultStr;
                   }
                    
            }else{
                echo "Input something...";
            } elseif($msgType=='image'){
                //回复类型,如果为text,代表文本类型
                $msgType="text";
                //回复内容
                $contentStr="您发送的是图片消息";
                //格式化字符串
                $resultStr=sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                //把XML数据返回给手机端
                echo $resultStr;
            }   elseif ($msgType=='location'){
                //回复类型
                $msgType='text';
                //回复内容
                $contentStr = "您发送的是地理位置消息,您的位置：经度{$longitude},纬度{$latitude}";
                //格式化字符串
                $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
                //返回xml数据到微信客户端
                echo $resultStr;
            }
        }else{
            echo "";
            exit;
        }
    }
	
    private function checkSignature(){
        //判断token密钥是否定义
        if(!defined("TOKEN")){
            throw new Exception('TOKEN is not defined');
        }
        $signature=$_GET["signature"];//微信加密签名
        $timestamp=$_GET["timestamp"];//时间戳
        $nonce=$_GET["nonce"];//随机数
        $token=TOKEN;
        $tmpArr=array($token,$timestamp,$nonce);
       //通过字典法进行排序
        sort($tmpArr,SORT_STRING);
        $tmpStr=implode($tmpArr);
        $tmpStr=sha1($tmpStr);
        if($tmpStr==$signature){
            return true;
        }else{
            return false;
        }
    }
}

