<?php

// app/Controller/PostsController.php
class PostsController extends AppController{
	public function index(){
	
	}
	
	public $paginate = array(
				'limit' => 3,
				'order' => array(
					'Post.created' => 'DESC',
				),
				'conditions' => array(
					'Post.id <' => 300
				)
			);
			
	public function getlist(){
		//Postモデルのデータを取得する、追加の条件も指定可
		$data = $this->paginate('Post',array(
			'Post.id not' => null
		));
		$this->set('data',$data);
	
	}

}
