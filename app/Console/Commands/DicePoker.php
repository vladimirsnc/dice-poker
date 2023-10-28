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

    protected array $diceFaces = ["⚀", "⚁", "⚂", "⚃", "⚄", "⚅"];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $diceHandPure = $this->generateDiceHand();

        // this sort may place into generateDiceHand method,
        // but I think, $diceHandPure may be needed somewhere else
        asort($diceHandPure);
        $diceHand = array_values($diceHandPure);

        $diceHandStat = [];
        $diceResult = 0;
        $diceResultStat = [];

        $straightCount = 0;
        $diceStraightStat = [];

        foreach ($diceHand as $key => $dice) {
            if (isset($diceHandStat[$dice])) {
                $diceHandStat[$dice]++;

                switch ($diceHandStat[$dice]) {
                    case 2:
                    case 5:
                        $diceResult = $diceResult + 1; // need for $diceResultLabels
                        $diceResultStat[$dice] = $diceHandStat[$dice];
                        break;
                    case 3:
                    case 4:
                        $diceResult = $diceResult + 2; // need for $diceResultLabels
                        $diceResultStat[$dice] = $diceHandStat[$dice];
                        break;
                }
            } else {
                $diceHandStat[$dice] = 1;
            }

            // We can exclude case, when $diceResult more than 1.
            // It means that hand have two pair or more strong variants
            // and straight not possible. But it actual only for 5 dice in hand
            if ($key < (count($diceHand) - 1)) {
                // check if $diceStraightStat have last element and set it in $diceForCheck
                // IMPORTANT! Dice value seted like key
                if (isset($diceStraightStat[count($diceStraightStat) - 1])) {
                    $diceForCheck = array_key_last($diceStraightStat);
                } else {
                    $diceForCheck = $dice;
                }

                if (1 === ($diceHand[$key + 1] - $diceForCheck)) {
                    $straightCount++;
                    $diceStraightStat[$dice] = 1;
                    $diceStraightStat[$diceHand[$key + 1]] = 1;
                }
            }
        }

        // because $straightCount for small straight is 3, and for big straight is 4
        if (2 < $straightCount) {
            $offset = 4; // need for $diceResultLabels
            $diceResult = $straightCount + $offset;
            $diceResultStat = $diceStraightStat;
        }

        $diceResultLabels = [
            1 => 'one pair',
            2 => 'two pair',
            3 => 'three of a kind',
            4 => 'full house',
            5 => 'four of a kind',
            6 => 'poker',
            7 => 'small straight',
            8 => 'big straight',
        ];

        $this->newLine();

        if ($diceResult) {
            $this->info("Congratulations! You have a $diceResultLabels[$diceResult]!");

            foreach ($diceResultStat as $dice => $count) {
                echo str_repeat($this->diceFaces[$dice] . ' ', $count);
            }

            $this->newLine();
        } else {
            $this->warn("It's just a chance... Try again!");
        }
    }

    /**
     * Generate dice hand - array of keys for diceFaces
     *
     * @param bool $testHand
     *
     * @return integer[]
     */
    protected function generateDiceHand(bool $testHand = false): array
    {
        $diceHand = [];

        if (!$testHand) {
            $diceCount = 5;

            for($i = 0; $i < $diceCount; $i++) {
                $dice = rand(0, 5);
                $diceHand[] = $dice;
                echo $this->diceFaces[$dice] . " ";
            }
        } else {
//            $testHand = [0, 2, 1, 0, 5];
//            $testHand = [0, 1, 3, 0, 1];
//            $testHand = [0, 0, 1, 2, 0];
//            $testHand = [0, 1, 1, 0, 1];
//            $testHand = [0, 0, 1, 0, 0];
//            $testHand = [0, 0, 0, 0, 0];
//            $testHand = [1, 0, 2, 5, 4];
//            $testHand = [1, 2, 3, 4, 1];
//            $testHand = [5, 1, 2, 3, 4];
            $testHand = [0, 1, 2, 5, 4];

            foreach ($testHand as $dice) {
                $diceHand[] = $dice;
                echo $this->diceFaces[$dice] . " ";
            }
        }

        $this->newLine();

        return $diceHand;
    }
}
