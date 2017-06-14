<?php
	
	// 定义TOKEN
	define('TOKEN','lamp26');

	class Wechat
	{

		// 接收到微信服务器发送给本地服务器的XML数据
		public function valid()
		{
			$echoStr = $_GET["echostr"];

			// 如果checkSignature()返回true，则说明此次请求是来自微信服务器的，原样返回$echoStr
			// 接入成功之后，微信服务器不会再发送$_GET['echostr']
			if($this->checkSignature() && $echoStr){
				echo $echoStr;
				exit;
			}else{
				// 接入成功之后都执行此区间
				$this->getData();
			}
		}

		// 接收微信服务器发送过来的XML数据
		public function getData()
		{
			// 1.拿到微信服务器以post方式发送的XML数据
			$xml = file_get_contents('php://input'); // php://input拿到POST的数据

			// 写入本地文件
			// file_put_contents('./runtime/1.txt' , $xml);

			libxml_disable_entity_loader(true);
			// 将XML格式的字符串转换为对象
			$xml_obj = simplexml_load_string($xml , 'SimpleXMLElement', LIBXML_NOCDATA);

			// 用户发送内容
			$content = $xml_obj->Content;
			// 发送人ud
			$this->openid = $xml_obj->FromUserName;
			// 接收方
			$this->wechatid = $xml_obj->ToUserName;

			// 根据发送的内容回复消息
			switch($content){
				case '@洗哼':
				case '拉洗哼':
				case 'P洗哼':
				case '茜洗哼':
					$this->replyText('是SB');
					break;
				case '狗霖':
				case '狗志':
				case '狗冲':
					$this->replyText('也是SB');
					break;
				case '图文消息':
				case '垃圾商城':
					$data = array(

						array(
							'Title' => '锐雯 - 黎明之刃',
							'Description' => '全新皮肤来袭！',
							'PicUrl' => 'http://joymepic.joyme.com/article/uploads/allimg/201706/1496729306250990.jpg?watermark/1/image/aHR0cDovL2pveW1lcGljLmpveW1lLmNvbS9hcnRpY2xlL3VwbG9hZHMvMTYwODE5LzgwLTE2MFE5MUZaMzQzOC5wbmc=/dissolve/70/gravity/SouthEast/ws/0.13',
							'Url' => 'http://lol.qq.com',
						),
						array(
							'Title' => '亚索 - 黑夜之刃',
							'Description' => '全新皮肤来袭！',
							'PicUrl' => 'http://joymepic.joyme.com/article/uploads/allimg/201706/1496729894720496.jpg?watermark/1/image/aHR0cDovL2pveW1lcGljLmpveW1lLmNvbS9hcnRpY2xlL3VwbG9hZHMvMTYwODE5LzgwLTE2MFE5MUZaMzQzOC5wbmc=/dissolve/70/gravity/SouthEast/ws/0.13',
							'Url' => 'http://lol.qq.com',
						),

					);
					$this->replyNews($data);
					break;
				default:
					$this->replyText('无知鲁讲咪列个');
					break;
			}

			// json_encode只会存储对象的成员属性，不会有成员方法
			// $tmp = json_encode($xml_obj);
			// file_put_contents('./runtime/2.txt' , $tmp);
		}

		/**
		 * replyText	回复文本消息
		 * @Author   WuAd1
		 * @DateTime 2017-06-07
		 * @param    [string] $data [回复用户的消息]
		 * @return   void
		 */
		protected function replyText($data)
		{
			$msgTpl = '<xml>
			<ToUserName><![CDATA['.$this->openid.']]></ToUserName>
			<FromUserName><![CDATA['.$this->wechatid.']]></FromUserName>
			<CreateTime>'.time().'</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA['.$data.']]></Content>
			</xml>';

			echo $msgTpl;
			exit;
		}

		/**
		 * replyNews	回复图文消息
		 * @Author   WuAd1
		 * @DateTime 2017-06-07
		 * @param    [array] $data [传入的图文消息数据]
		 */
		protected function replyNews($data){
			$newsTpl = '<xml>
			<ToUserName><![CDATA['.$this->openid.']]></ToUserName>
			<FromUserName><![CDATA['.$this->wechatid.']]></FromUserName>
			<CreateTime>'.time().'</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<ArticleCount>'.count($data).'</ArticleCount>
			<Articles>';

				foreach($data as $v)
				{
					$newsTpl .= '<item>
					<Title><![CDATA['.$v['Title'].']]></Title> 
					<Description><![CDATA['.$v['Description'].']]></Description>
					<PicUrl><![CDATA['.$v['PicUrl'].']]></PicUrl>
					<Url><![CDATA['.$v['Url'].']]></Url>
					</item>';
				}

			$newsTpl .= '</Articles></xml>';

			echo $newsTpl;
			exit;
		}

		private function checkSignature()
		{
			// you must define TOKEN by yourself
			if (!defined("TOKEN")) {
				throw new Exception('TOKEN is not defined!');
			}

			$signature = $_GET["signature"];
			$timestamp = $_GET["timestamp"];
			$nonce = $_GET["nonce"];

			$token = TOKEN;
			$tmpArr = array($token, $timestamp, $nonce);
			// use SORT_STRING rule
			sort($tmpArr, SORT_STRING);
			$tmpStr = implode( $tmpArr );
			$tmpStr = sha1( $tmpStr );

			if( $tmpStr == $signature ){
				return true;
			}else{
				return false;
			}
		}

	}

	// 实例化Wechat
	$wechat = new Wechat();
	$wechat->valid();

