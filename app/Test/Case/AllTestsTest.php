<?php
// app/Test/Case/AllTestsTest.php

class AllTests extends PHPUnit_Framework_TestSuite{
	public static function suite(){
		$suite = new CakeTestSuite('アプリケーション全テスト');
		
		$suite->addTestFile(
			APP_TEST_CASES . DS . 'Model' . DS . 'TopicTest.php');
		$suite->addTestDirectory(APP_TEST_CASES . DS . 'Controller');
		return $suite;
	}
}		