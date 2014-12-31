<?php

/*
  Liveコントローラー
 *  Twitter
 *  Instgram
 *  Youtube
 *  RSS
 *  Toto
 *  TV放送
 *  試合経過、結果
 *  */

class LivesController extends AppController{
    
    public $uses = array('POST','Live');    //使用するモデルを宣言
    //var $name = "Api";
    
    public $timeline = array();
    
    public function index(){
        //ここで情報を処理
        //処理記述したら共通化してコンポーネントにする
        
        /** Userコントローラーから情報を受け取る 
         * 
         **/
        //Sessionからトークン情報を取得
        $twitter_token = $this->Session->read('twitter_token');
        //debug($twitter_token);
        
        //tmhOuthオブジェクトの生成
        $tmhOAuth = $this->createTmhOAuth($twitter_token);
        //debug($tmhOauth);
        
       /*Twitterの検索結果を取得*/
       $this->searchTwtter($tmhOAuth, 'セレッソ大阪', 8);
        
       /* Twitterのホームラインを取得*/
       $timeline = $this->Session->read('hometime_line');
       //debug($timeline);
      
       //ホームラインの取得結果から情報取り出し
       foreach ($timeline as $val){
           $timeline['text'][] = $val->text;
           $timeline['name'][] = $val->user->name;

       }
       //debug($timeline['text']);
       //debug($timeline['name']);
    }

    /*Twitter 検索結果取得用メソッド
     * $tmhOAuth tmhOAuthインスタンス
     * $keyword 検索キーワード
     * $count 取得件数
     *  */
    public function searchTwtter($tmhOAuth, $keyword, $count){
        /**
         * requestメソッドでTwitterAPIにリクエストを送る
         * Twitterの検索結果を取得
 	 */
                  
        $status = $tmhOAuth->request(
                "GET", // リクエストメソッド
                 $tmhOAuth->url("1.1/search/tweets.json"), // エンドポイントを指定
                 array('q' => urlencode($keyword), 'count' => $count));

        /**
        * requestの返り値はHTTPのステータスコード
        *
        */

        if($status == 200){
              /**
              * データはメンバのresponseの中に、
              * さらに生のデータはその中の"response"の中にJSON形式で格納されている
              */

              $response = $tmhOAuth->response;
              //debug($response['response']);
              $data_j = json_decode($response['response']);
              debug($data_j);

        }
        else{
            echo "リクエストの値が取得できませんでした";
        }

    }
                
    //tmhインスタンス生成して返す
    public function createTmhOAuth($data){
        $tmhOAuth = new tmhOAuth(
 		array(
 		  	"consumer_key" => "pkOywXf82gAyn1hnpqgzShmHX",
                        "consumer_secret" => "ajN7sqnlvJEmqgQyaXRZ7m2bkSGWGIcsWF6ft9Alm0jmpuGlgE",
 		  	"token" => $data['auth']['credentials']['token'],
                        "secret" =>$data['auth']['credentials']['secret'],
 		  )
 	);
        return $tmhOAuth;
    }
                
    public function opauthComplete() {
 		  
 	 /**
         * requestメソッドでTwitterAPIにリクエストを送る
 		  * ここではユーザーのホームラインを取得
 	 */
                  
 		  $status = $tmhOAuth->request(
                          "GET", // リクエストメソッド
                           $tmhOAuth->url("1.1/statuses/home_timeline.json"), // エンドポイントを指定
                           array( "count" => 8 ) // パラメータ
                 );

                  /**
 		  * requestの返り値はHTTPのステータスコード
 		  *
 		  */
                  
 		  if($status == 200){
 		  	/**
 		  	* データはメンバのresponseの中に、
 		  	* さらに生のデータはその中の"response"の中にJSON形式で格納されている
 		  	*/
                  
 		  	$response = $tmhOAuth->response;
                        //debug($response['response']);
 		  	$this->twitter_timelne = json_decode($response['response']);
 		  	debug($data_j);
                        
 		  }
 		  else{
                      echo "リクエストの値が取得できませんでした";
                  }
    }    
}