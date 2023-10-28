<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DicePoker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dice-poker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "It's just dice poker. Enjoy!";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $diceFaces = ["⚀", "⚁", "⚂", "⚃", "⚄", "⚅"];
        $diceCount = 5;
        $diceHand = [];

        for($i = 0; $i < $diceCount; $i++) {
            $dice = rand(0, 5);
            $diceHand[] = $dice;
            echo $diceFaces[$dice] . " ";
        }

//        $testHand = [0, 1, 2, 4, 5];
//
//        foreach ($testHand as $dice) {
//            $diceHand[] = $dice;
//            echo $diceFaces[$dice] . " ";
//        }

//        $this->newLine();
//
//        asort($diceHand);
//
//        $this->line($diceHand);

        $diceHandStat = [];
        $diceResult = 0;
        $diceResultStat = [];

        foreach ($diceHand as $dice) {
            if (isset($diceHandStat[$dice])) {
                $diceHandStat[$dice]++;

                switch ($diceHandStat[$dice]) {
                    case 2:
                    case 5:
                        $diceResult = $diceResult + 1;
                        $diceResultStat[$dice] = $diceHandStat[$dice];
                        break;
                    case 3:
                    case 4:
                        $diceResult = $diceResult + 2;
                        $diceResultStat[$dice] = $diceHandStat[$dice];
                        break;
                }
            } else {
                $diceHandStat[$dice] = 1;
            }
        }

        /*
         * $diceResult:
         *     1 - one pair
         *     2 - two pair
         *     3 - three of a kind
         *     4 - full house
         *     5 - four of a kind
         *     6 - poker
         */

//        $this->newLine();
//        print_r($diceHandStat);
//        $this->newLine();
//        $this->line($diceResult);
//        $this->newLine();
//        print_r($diceResultStat);

        $diceResultLabels = [
            1 => 'one pair',
            2 => 'two pair',
            3 => 'three of a kind',
            4 => 'full house',
            5 => 'four of a kind',
            6 => 'poker',
        ];

        $this->newLine();

        if ($diceResult) {
            $this->info("Congratulations! You have a {$diceResultLabels[$diceResult]}!");

            foreach ($diceResultStat as $dice => $count) {
                echo str_repeat($diceFaces[$dice] . ' ', $count);
            }

            $this->newLine();
        } else {
            $this->warn("It's just a chance... Try again!");
        }
    }
}
