<?php

class PlayerTest extends \PHPUnit_Framework_TestCase
{
    public function testInitPlayer()
    {
        $player = new Player('Gonzalo');
        $this->assertEquals("Gonzalo", $player->getName());
    }

    public function testPlayerScores()
    {
        $player = new Player('Gonzalo');
        $this->assertEquals(0, $player->getScore());
        $this->assertEquals(TRUE, $player->incrementScore());
        $this->assertEquals(15, $player->getScore());
        $this->assertEquals(TRUE, $player->incrementScore());
        $this->assertEquals(30, $player->getScore());
        $this->assertEquals(TRUE, $player->incrementScore());
        $this->assertEquals(40, $player->getScore());
        $this->assertEquals(FALSE, $player->incrementScore());
        $this->assertEquals(40, $player->getScore());
    }
}