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

class LiveController extends AppController{
    
    public $uses = array('POST','User','Live');    //使用するモデルを宣言
    public function index(){
        //ここで情報を処理
        //処理記述したら共通化してまとめる
        
        /*Userコントローラーから情報を受け取る*/
       
        
    }
    
}