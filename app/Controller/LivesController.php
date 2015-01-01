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

App::uses('Folder','Utility');
App::uses('File', 'Utility');

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
       $s_result =$this->searchTwtter($tmhOAuth, 'セレッソ大阪', 8);
       //debug($s_result);
       $s_result = $s_result->statuses;
       //debug($s_result);
       $search_result = array();
       //検索結果から情報の取り出し
       foreach($s_result as $var){
           $search_result['text'][] = $var->text;
           $search_result['name'][] = $var->user->name;
       }
       //debug($search_result['text']);
       //debug($search_result['name']);
       
       /*Twitter ハッシュタグ検索 */
       //ファイルの読み込み
       
       /*ファイルディレクトリ探査サンプル*/
       //$dir = new Folder(APP,'path/');
       //debug($dir->read());
       
       
       //ハッシュタグのファイルを読み込み
       /* File クラス使用の場合 */
       ////$file = new File(APP.'\Text/team_hashtag.txt');
       //$file_info = $file->read();
       //debug($file_info);
       //$file->close();
       
       /* fopen使用 */
       $handle = fopen(APP.'/Text/team_hashtag.txt','r');
       $hashtag_temp = array();
       $hashtag = array();
       
       $count = count(file(APP.'/Text/team_hashtag.txt'));
       //debug($count);
       
       for($i = 0; $i < $count; $i++){
           $hashtag_temp[] = fgets($handle);
       }
       fclose($handle); //ファイルを閉じる
       
       //ファイルから取得した情報でハッシュタグを取得
       for($i = 0; $i < count($hashtag_temp); $i++){
           $temp = split(',', $hashtag_temp[$i]);
           $hashtag[$temp[0]] = $temp;
           //連想配列の中身からチーム名を除く（先頭)
           //「キー」→「ハッシュタグ」の配列にする
           unset($hashtag[$temp[0]][0]);
           $hashtag[$temp[0]] = array_merge($hashtag[$temp[0]]);    //添え字を詰める
           
       }
       
       $team_name = "セレッソ大阪";
       $hashtag_result = array();   //ハッシュタグ結果保持
       //ハッシュタグ検索処理
       $h_result = $this->searchHashtag($tmhOAuth, $hashtag[$team_name], 8);
       //ハッシュタグの取得結果から情報取り出し
       //debug($h_result);
       $h_result = $h_result->statuses;
       
       //検索結果から情報の取り出し
      
       foreach($h_result as $var){
           $hashtag_result['text'][] = $var->text;
           $hashtag_result['name'][] = $var->user->name;
       }
       debug($hashtag_result);
       
       
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
     * $hashtag 検索キーワード
     * $count 取得件数
     *  */
    public function searchHashtag($tmhOAuth, $hashtag, $count){
        /**
         * requestメソッドでTwitterAPIにリクエストを送る
         * Twitterの検索結果を取得
 	 */
        
        $hashtag_1; //検索に使用するハッシュタグ
        
        if(count($hashtag) > 1){
            //指定したハッシュタグが複数ある場合
            //1つ目のハッシュタグを指定
            $hashtag_temp = $hashtag[0];
        }else if(count($hashtag) === 0 ){
            //ハッシュタグを取得できなかった場合
            return false;
        }else{
            //ハッシュタグを取得できた場合
            $hashtag_1 = $hashtag[0];
        }
        
        /*ハッシュタグ（ワード）に先頭#が含まれているか
         * 先頭の#が含まれている場合、#を取り除く
         */
        /*  使用しないのでコメントアウト   */
        //if(preg_match('/^#.*/', $hashtag_1)){
        //    先頭の#を取り除く
        //    $temp = preg_replace('/^\w.*/', $hashtag_1,$hashtag_1);
        //    $hashtag_1 = str_replace('#', '', $hashtag_1);
        //
        //}
        
        //debug($hashtag_1);
        
        $status = $tmhOAuth->request(
                "GET", // リクエストメソッド
                 $tmhOAuth->url("1.1/search/tweets.json"), // エンドポイントを指定
                 array('q' => urlencode($hashtag_1), 'count' => $count));

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
              //debug($data_j);
              return $data_j;
        }
        else{
            echo "リクエストの値が取得できませんでした";
        }

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
              //debug($data_j);
              return $data_j;
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
 		  	//debug($data_j);
                        
 		  }
 		  else{
                      echo "リクエストの値が取得できませんでした";
                  }
    }    
}