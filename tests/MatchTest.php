<?php

class MatchTest extends \PHPUnit_Framework_TestCase
{
    public function testEndMatchPlayer1Wins()
    {
        $player1 = $this->getMockBuilder('Player')
                ->disableOriginalConstructor()
                ->getMock();
        $player1->expects($this->any())->method('getScore')->will($this->returnValue(40));
        $player1->expects($this->any())->method('getName')->will($this->returnValue('Gonzalo'));

        $player2 = $this->getMockBuilder('Player')
                ->disableOriginalConstructor()
                ->getMock();
        $player2->expects($this->any())->method('getScore')->will($this->returnValue(0));

        $endMatch = FALSE;
        $match = new Match($player1, $player2);
        $match->registerEndCallback(
            function() use (&$endMatch) {
                $endMatch = TRUE;
            }
        );
        $this->assertFalse($endMatch);
        $match->incrementScorePlayer1();

        $this->assertTrue($endMatch, 'Match is over');
        $this->assertEquals($match->getWinner()->getName(), 'Gonzalo');
    }

    public function testEndMatchPlayer2Wins()
    {
        $player2 = $this->getMockBuilder('Player')
                ->disableOriginalConstructor()
                ->getMock();
        $player2->expects($this->any())->method('getScore')->will($this->returnValue(40));
        $player2->expects($this->any())->method('getName')->will($this->returnValue('Peter'));

        $player1 = $this->getMockBuilder('Player')
                ->disableOriginalConstructor()
                ->getMock();
        $player1->expects($this->any())->method('getScore')->will($this->returnValue(30));

        $endMatch = FALSE;
        $match = new Match($player1, $player2);
        $match->registerEndCallback(
            function() use (&$endMatch) {
                $endMatch = TRUE;
            }
        );
        $this->assertFalse($endMatch);
        $match->incrementScorePlayer2();

        $this->assertTrue($endMatch, 'Match is over');
        $this->assertEquals($match->getWinner()->getName(), 'Peter');
    }

    public function testDeuce()
    {
        $player1 = $this->getMockBuilder('Player')
                ->disableOriginalConstructor()
                ->getMock();
        $player1->expects($this->any())->method('getScore')->will($this->returnValue(40));

        $player2 = $this->getMockBuilder('Player')
                ->disableOriginalConstructor()
                ->getMock();
        $player2->expects($this->at(1))->method('getScore')->will($this->returnValue(30));
        $player2->expects($this->at(2))->method('getScore')->will($this->returnValue(40));

        $endMatch = $deuce = FALSE;

        $match = new Match($player1, $player2);
        $match->registerEndCallback(function() use (&$endMatch) {
                $endMatch = TRUE;
            });

        $match->registerDeuceCallback(function() use (&$deuce) {
                $deuce = TRUE;
            });

        $this->assertFalse($deuce, 'No Deuce');
        $match->incrementScorePlayer2();
        $this->assertTrue($deuce, 'Deuce');
    }

    public function testAdvances()
    {
        $player1 = $this->getMockBuilder('Player')
                ->disableOriginalConstructor()
                ->getMock();
        $player1->expects($this->any())->method('getScore')->will($this->returnValue(40));
        $player1->expects($this->any())->method('getName')->will($this->returnValue('Gonzalo'));

        $player2 = $this->getMockBuilder('Player')
                ->disableOriginalConstructor()
                ->getMock();
        $player2->expects($this->any())->method('getScore')->will($this->returnValue(40));
        $player2->expects($this->any())->method('getName')->will($this->returnValue('Peter'));

        $endMatch = $deuce = FALSE;
        $userWithAdvance = NULL;

        $match = new Match($player1, $player2);
        $match->registerAdvanceCallback(function() use ($match, &$userWithAdvance) {
                $userWithAdvance = $match->getUserWithAdvance();
            });

        $match->registerEndCallback(function() use (&$endMatch) {
                $endMatch = TRUE;
            });

        $this->assertNull($userWithAdvance);
        $match->incrementScorePlayer1();
        $this->assertEquals('Gonzalo', $userWithAdvance->getName('Gonzalo'));

        $match->incrementScorePlayer2();
        $this->assertNull($match->getUserWithAdvance());

        $match->incrementScorePlayer1();
        $this->assertEquals('Gonzalo', $userWithAdvance->getName('Gonzalo'));

        $match->incrementScorePlayer1();
        $this->assertTrue($endMatch, 'Match is over');
        $this->assertEquals($match->getWinner()->getName(), 'Gonzalo');
    }
}