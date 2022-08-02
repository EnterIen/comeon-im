<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
  /**
     * 新建一个类的静态成员，用来保存数据库实例
     */
    public static $db = null;

    /**
     * 进程启动后初始化数据库连接
     */
    public static function onWorkerStart($worker)
    {
      self::$db = new \Workerman\MySQL\Connection('47.105.106.78', 3306, 'root', 'root', 'comeon','utf8mb4');
    }
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        // 向当前client_id发送数据 
        // Gateway::sendToClient($client_id, "Hello $client_id\r\n");
        // 向所有人发送
        // Gateway::sendToAll("$client_id login\r\n");
    }

    public static function onWebSocketConnect($client_id, $data)
    {    
      // if (!isset($data['u_id']) || empty($data['u_id'])) {
      //   Gateway::closeClient($client_id, 'fail');
      // }

      /**
       * 第一步
       * { websocket 握手成功, 绑定 client_id  u_id }
       */
      Gateway::bindUid($client_id, $data['u_id']);

      /**
       * 第二步
       * { 告诉客户端握手成功 }
       */
      Gateway::sendToClient($client_id, 'success');

      /**
       * 第三步
       * { 客户端去建立会话(用户端) }
       */

      // // 向当前client_id发送数据 
      // Gateway::sendToClient($client_id, "Hello $client_id\r\n");
      // // 向所有人发送
      // Gateway::sendToAll("$client_id login\r\n");
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {
      /**
       * { 接收消息-持久化 }
       */
      // $message = json_decode($message, true);

      $insert = [
        'talk_id' => 1,
        'user_id' => 1,
        'kefu_id' => 666,
        'from'    => 1,
        'content' => $message ? $message : '没有收到客户端消息'
      ];

      $res = self::$db->insert('messages')->cols($insert)->query();


      /**
       * { 根据 from_type 找到对应接收端-投递消息 }
       */

      // 向所有人发送 
      if ($res) {
        Gateway::sendToAll('已收到并持久化');
      }

      Gateway::sendToAll('已收到未持久化');
   }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {
       // 向所有人发送 
       GateWay::sendToAll("$client_id logout\r\n");
   }
}
