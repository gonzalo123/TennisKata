<?php

class Player
{
    private $name;
    private $score = 0;
    private static $scoreDictionary = [0, 15, 30, 40];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getScore()
    {
        return self::$scoreDictionary[$this->score];
    }

    public function incrementScore()
    {
        if ($this->score < 3) {
            $this->score++;
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
 
