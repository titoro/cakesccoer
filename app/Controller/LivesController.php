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
        
        /** Userコントローラーから情報を受け取る **/
       //Twitterのホームラインを取得
       $timeline = $this->Session->read('hometime_line');
       //debug($timeline);
       
       foreach ($timeline as $val){
           $timeline['text'][] = $val->text;
           $timeline['name'][] = $val->user->name;

       }
       debug($timeline['text']);
       debug($timeline['name']);
    }


    public function search(){

                    //$this->autoLayout = false;
                    $options = array('q'=>'soccer','count'=>'3','lang'=>'ja');

                    echo $this->TwitterOAuth->OAuthRequest(
                        'https://api.twitter.com/1.1/search/tweets.json',
                        'GET',
                        $options
                    );
                    exit;

    }
                
                
		public function opauthComplete() {
 		  //debug($this->data);
 		  
 		  //$tmhUtil = new tmhUtilities();
		  //$here = $tmhUtil->php_self();
 		  
 		  /**
 		  * tmhOAuthを初期化
 		  * コンストラクタの引数はトークンをまとめた連想配列
 		  */
                  //debug($this->data);
                  //$data = $this->data;
                  
                  //$data_j[] = $this->User->twitter_timeline($data);
                  
                  //debug($data_j);
                  
                  
 		  $tmhOAuth = new tmhOAuth(
 		  			array(
 		  				"consumer_key" => "pkOywXf82gAyn1hnpqgzShmHX",
 		  				"consumer_secret" => "ajN7sqnlvJEmqgQyaXRZ7m2bkSGWGIcsWF6ft9Alm0jmpuGlgE",
 		  				"token" => $this->data['auth']['credentials']['token'],
 		  				"secret" =>$this->data['auth']['credentials']['secret'],
 		  			)
 		  );
 		  
 		  /**
 		  * requestメソッドでTwitterAPIにリクエストを送る
 		  * ここではユーザーのホームラインを取得
 		  */
                  
 		  $status = $tmhOAuth->request(
                          "GET", // リクエストメソッド
                           $tmhOAuth->url("1.1/statuses/home_timeline.json"), // エンドポイントを指定
                           array( "count" => 8 ) // パラメータ
                 );
 		  		//'method'=> 'GET',
 		  		//'url' => $twitter->url('1.1/statuses/user_timeline.json'),	//エンドポイントを指定
 		  		//'params' => array("count" => 8)	//パラメータ
 		  //));
 		  
                   debug($status);
                  
                   //debug($tmhOAuth->response);
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
                        
                        //リダイレクト（テストページ)
                        //$this->redirect(array('controller' => 'Live', 'action' => 'index'));
 		  }
 		  else{
                      echo "リクエストの値が取得できませんでした";
                  }
    }    
}