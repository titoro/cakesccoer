<?php

/* 
  Twitter関連のコンポーネント
 *  */

class TwitterComponent extends Component{
    //特定ユーザーのタイムラインを取得
    public function searchUserTimeline($tmhOAuth, $screenname,$count){
       
         $status = $tmhOAuth->request(
                "GET", // リクエストメソッド
                 $tmhOAuth->url("1.1/statuses/user_timeline.json"), // エンドポイントを指定
                 array('screen_name' => urldecode($screenname),
                     'count' => $count,
                      ));

        /**
        * requestの返り値はHTTPのステータスコード
        */

        if($status == 200){
              /**
              * データはメンバのresponseの中に、
              * さらに生のデータはその中の"response"の中にJSON形式で格納されている
              */

              $response = $tmhOAuth->response;
              //debug($response['response']);
              $data_j = json_decode($response['response'],true);
              //debug($data_j);
              return $data_j;
        }
        else{
            echo 'リクエストを取得できませんでした';
        }
    }
    
    //エンティティ情報付きのツイート検索用メソッド
    public function searchWithEntities($tmhOAuth, $hashtag,$count){
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
         $status = $tmhOAuth->request(
                "GET", // リクエストメソッド
                 $tmhOAuth->url("1.1/search/tweets.json"), // エンドポイントを指定
                 array('q' => urlencode($hashtag_1),
                     'count' => $count,
                     'include_entities' => '1'));

        /**
        * requestの返り値はHTTPのステータスコード
        */

        if($status == 200){
              /**
              * データはメンバのresponseの中に、
              * さらに生のデータはその中の"response"の中にJSON形式で格納されている
              */

              $response = $tmhOAuth->response;
              //debug($response['response']);
              $data_j = json_decode($response['response'],true);
              //debug($data_j);
              return $data_j;
        }
    }
}
