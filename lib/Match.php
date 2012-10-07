<?php

class Match
{
    private $players;
    private $endCallback;
    private $deuceCallback;
    private $advanceCallback;

    private $playerIdWithAdvance;
    private $winner = NULL;

    const PLAYER1 = 0;
    const PLAYER2 = 1;

    public function __construct(Player $player1, Player $player2)
    {
        $this->players[self::PLAYER1] = $player1;
        $this->players[self::PLAYER2] = $player2;
    }

    public function getWinner()
    {
        return $this->winner;
    }

    public function incrementScorePlayer1()
    {
        return $this->incrementScorePlayer(self::PLAYER1);
    }

    public function incrementScorePlayer2()
    {
        return $this->incrementScorePlayer(self::PLAYER2);
    }

    private function incrementScorePlayer($player)
    {
        $out = $this->players[$player]->incrementScore();
        if ($this->checkIfPlayerIncrementEndsMatch($player)) {
            $this->execCallback($this->endCallback);
        }

        if ($this->isDeuce()) {
            $this->execCallback($this->deuceCallback);
        }
        return $out;
    }

    private function isDeuce()
    {
        return $this->getScoreFromPlayer(self::PLAYER1) == 40 && $this->getScoreFromPlayer(self::PLAYER2) == 40;
    }

    private function checkIfPlayerIncrementEndsMatch($player)
    {
        if ($this->isDeuce()) {
            if ($this->playerIdWithAdvance === $player) {
                return $this->playerWinMatch($player);
            } else {
                $this->processAdvances($player);
            }
        } else {
            if ($this->getScoreFromPlayer(self::PLAYER1) == 40 || $this->getScoreFromPlayer(self::PLAYER2) == 40) {
                return $this->playerWinMatch($player);
            } else {
                return FALSE;
            }
        }
    }

    private function processAdvances($player)
    {
        if (is_null($this->playerIdWithAdvance)) {
            $this->registerAdvanceToPlayer($player);
        } else {
            $this->registerAdvanceToPlayer();
        }
    }

    private function registerAdvanceToPlayer($player = null)
    {
        $this->playerIdWithAdvance = $player;
        if (!is_null($player)) {
            $this->execCallback($this->advanceCallback);
        }
    }

    private function execCallback($callback)
    {
        if (is_callable($callback)) {
            call_user_func($callback);
        }
    }

    private function playerWinMatch($player)
    {
        $this->winner = $this->players[$player];
        return TRUE;
    }

    public function getUserWithAdvance()
    {
        return is_null($this->playerIdWithAdvance) ? NULL : $this->players[$this->playerIdWithAdvance];
    }

    private function getScoreFromPlayer($player)
    {
        return $this->players[$player]->getScore();
    }

    public function registerEndCallback(Closure $closure)
    {
        $this->endCallback = $closure;
    }

    public function registerDeuceCallback(Closure $closure)
    {
        $this->deuceCallback = $closure;
    }

    public function registerAdvanceCallback(Closure $closure)
    {
        $this->advanceCallback = $closure;
    }
}