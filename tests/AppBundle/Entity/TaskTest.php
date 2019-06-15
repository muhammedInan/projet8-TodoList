<?php

namespace Tests\AppBundle\Entity;

use Tests\AppBundle\Controller\WebTestCase;
use AppBundle\Entity\Task;

class TaskTest extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    public function testAddNewTask()
    {
        $task = new Task();
        $task->setTitle('Titre de ma tâche');
        $task->setContent('Description de ma tâche');
        $this->assertInstanceOf('DateTime', $task->getCreatedAt());
        $this->assertSame('Titre de ma tâche', $task->getTitle());
        $this->assertSame('Description de ma tâche', $task->getContent());
        $this->assertFalse($task->isDone());
    }
    public function testTaskBlankDataSet()
    {
        $task = new Task();
        $task->setTitle('');
        $task->setContent('');
        $validator = static::$kernel->getContainer()->get('validator');
        $violationList = $validator->validate($task);
        $this->assertEquals(2, $violationList->count());
        $this->assertSame('Vous devez saisir un titre.', $violationList[0]->getMessage());
        $this->assertSame('Vous devez saisir du contenu.', $violationList[1]->getMessage());
    }
    protected function tearDown()
    {
        parent::tearDown();
    }
}
