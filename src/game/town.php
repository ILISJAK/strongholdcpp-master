<?php
class Town
{
    public $team;
    public $population;
    public $activePopulation;
    public $gold;
    public $rations;
    public $wood;
    public $stone;
    public $housing;


    public function __construct($team, $population, $activePopulation, $gold, $rations, $wood, $stone, $housing)
    {
        $this->team = $team;
        $this->population = $population;
        $this->activePopulation = $activePopulation;
        $this->gold = $gold;
        $this->rations = $rations;
        $this->wood = $wood;
        $this->stone = $stone;
        $this->housing = $housing;
    }
}
?>