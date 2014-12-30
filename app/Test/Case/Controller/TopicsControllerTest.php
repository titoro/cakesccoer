<?php
App::uses('TopicsController', 'Controller');

/**
 * TopicsController Test Case
 *
 */
class TopicsControllerTest extends ControllerTestCase {
	
/**
 * @expectedException NotFoundExcepton
 * @expectedExceptionMessage Invalid topic
 */
	
	public function testNotFoundError()
	{
		$this->testAction('/topics/view/999');
	}
	
	public function test�폜������������index�Ƀ��_�C���N�g����(){
		$this->testAction('/topics/delete/1', array('method' => 'post'));
		$this->assertRegExp('/topics$', $this->headers['Location']);
	}
	
	public function test�V�����g�s�b�N����������(){
		$data = array('Topic' => array('title' =>'�V�����g�s�b�N�^�C�g��'));
		$this->testAction('/topics/add',
					array('data' => $data, 'method' => 'post'));
		$this->assertContains('The topic has been saved',
			$this->controller->Session->read('Message.flash'));
	}

}
