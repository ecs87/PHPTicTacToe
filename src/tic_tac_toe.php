<?php
/*
 * Please fix each todo in this file in order.
 * Starting from the top.
 * When you are finished and run it, the tests should pass and you should be able to play.
 *
 * If you find other bugs, fix them as well.
 */




/**
 * Keeps track of pieces
 */
class Board
{
    const ROWS = 3;
    const COLUMNS = 3;

    /** @var string[][] */
    private $pieces;

    /**
     * @param string[][] $pieces
     */
    public function __construct($pieces = [])
    {
        $this->pieces = $this->fillBlanks($pieces);
    }

    /**
     * A visual representation of the game board
     * @return string
     */
    function __toString()
    {
        $string = '';
		$i = 0;
        foreach ($this->pieces as $column) {

            foreach ($column as $piece) {
            		if ($piece === '') {
                    $string .= ('-');
                    $i++;
                } else {
                    $string .= $piece;
                    $i++;
                }
            }
						$string .= ("\n");
        }

        return $string;
    }

    /**
     * @return bool True when there are no more empty spaces
     */
    public function isFull()
    {
        foreach ($this->pieces as $columns) {
            foreach ($columns as $piece) {
                if ($piece === '') {
                    return false;
                }
            }
        }

        return true;
    }


    /**
     * @return array Array of all possible positions [x,y]...
     */
    private function getAllPositions()
    {
        $positions = [];

        for ($y = 0; $y < self::ROWS; $y++) {
            for ($x = 0; $x < self::COLUMNS; $x++) {
                $positions[] = [$y, $x];
            }
        }

        return $positions;
    }

    /**
     * Define each possible row/column combination with an empty string ''
     * @param string[][] $pieces
     * @return string[][]
     */
    private function fillBlanks(array $pieces)
    {
        foreach ($this->getAllPositions() as list($y, $x)) {

            if (!isset($pieces[$y][$x])) {
                $pieces[$y][$x] = '';
            }
        }

        return $pieces;
    }

    /**
     * Todo
     * Return a list of positions (an array of [y, x] pairs) that have no piece (where the value is '')
     * @return int[][] A list of blank positions, [x, y]...
     */
    public function getBlankPositions($board)
    {
				$blanks = array();
				for ($j = 0; $j < 3; $j++) {
					for ($i = 0; $i < 3; $i++) {
						if ($board->getPieceAtPosition($j,$i) == '') {
							array_push($blanks, array($j, $i));
						}
					}
				}
        return $blanks;
    }

    /**
     * @param int $y Row
     * @param int $x Column
     * @param string $piece Piece, 'O' or 'X'
     * @return Board
     */
    public function addPiece($y, $x, $piece)
    {
        $this->pieces[$y][$x] = $piece;

        return $this;
    }

    public function getWinner($board, $movePosition)
    {
		$playerOrAIPiece = "";
		//column (Player)
		for ($i = 0; $i < 3; $i++) {
			if ($board->getPieceAtPosition($i, $movePosition->column) != Player::PIECE) {
				break;
			}
			if ($i == 3-1) {
				$playerOrAIPiece = Player::PIECE;
			}
		}
		//row (Player)
		for ($i = 0; $i < 3; $i++) {
			if ($board->getPieceAtPosition($movePosition->row, $i) != Player::PIECE) {
				break;
			}
			if ($i == 3-1) {
				$playerOrAIPiece = Player::PIECE;
			}
		}
		//diag1 (Player)
		if($movePosition->column == $movePosition->row){
		  //we're on a diagonal
		  for ($i = 0; $i < 3; $i++) {
			if ($board->getPieceAtPosition($i,$i) != Player::PIECE)
				break;
			if ($i == 3-1) {
				$playerOrAIPiece = Player::PIECE;
			}
		  }
		}
		//diag2 (Player)
		if ($movePosition->column + $movePosition->row == 3-1) {
		  //we're on a diagonal
		  for ($i = 0; $i < 3; $i++) {
			if ($board->getPieceAtPosition($i,(3 - 1)-$i) != Player::PIECE) {
				break;
			}
			if ($i == 3-1) {
				$playerOrAIPiece = Player::PIECE;
			}
		  }
		}
		return $playerOrAIPiece;
    }

    /**
     * @param int $y Row
     * @param int $x Column
     * @return string
     */
    public function getPieceAtPosition($y, $x)
    {
        return $this->pieces[$y][$x];
    }

    /**
     * @param int $y
     * @param int $x
     * @return bool
     */
    public function hasPieceAtPosition($y, $x)
    {
        return $this->getPieceAtPosition($y, $x) !== '';
    }

}
/**
 * Gets input from the player and places pieces on the board.
 * Contains some minimal validation to prevent:
 * * Placing pieces on top of pieces
 * * Placing pieces outside of the board
 */
class Player
{
    const PIECE = 'O';

    public function placePiece(Board $board)
    {
        list($y, $x) = $this->getMovePosition($board);
        $board->addPiece($y, $x, self::PIECE);
		$retPos = new stdClass;
		$retPos->row = $y;
		$retPos->column = $x;
		return $retPos;
    }

    private function getMovePosition(Board $board)
    {
        do {
            $position = [
                $this->getInput('Row'),
                $this->getInput('Col')
            ];
			echo $board->hasPieceAtPosition(2,2);
            $valid = !$board->hasPieceAtPosition($position[0], $position[1]);

            if (!$valid) {
                echo "Piece already at this position\n";
            }

        } while (!$valid);

        return $position;
    }

    private function getInput($prompt)
    {
        echo "$prompt: ";

        do {
            $input = readline();

            $valid = $input >= 0 && $input <= 2;

            if (!$valid) {
                echo "Input must be in range [0, 2]\n";
            }

        } while (!$valid);

        return $input;
    }
}

/**
 * Enemy player. Automatically places pieces on the board according to some rules.
 */
class AI
{
    const PIECE = 'X';

    /**
     * This function should call:
     *
     * $board->addPiece($y, $x, self::PIECE);
     *
     * use getMovePosition
     *
     * @param Board $board
     */
    public function placePiece(Board $board)
    {
        $this->getMovePosition($board);
		/*
		$valid = !$board->hasPieceAtPosition($y, $x);
		if (!$valid) { 
			//Piece already at this position
		} else { $board->addPiece($y, $x, self::PIECE); }
		*/
    }

    /**
     * Return the position the AI wants to place a piece at
     *
     * This function should return [$y, $x] or null
     *
     * $y is the 0-index row
     * $x is the 0-index column
     *
     * The AI should use this logic in order:
     * 1. Place a piece that makes the AI win
     * 2. Or place a piece that prevents the player from winning
     * 3. Or place the piece in any position
     *
     * @param Board $board
     * @return int[]|null
     */
    private function getMovePosition(Board $board)
    {
		$blank_spaces = $board->getBlankPositions($board);
		$position = NULL;
		$position = test_go_for_win($board, false);
		//var_dump($position);
		if ($position == NULL) {
			$position = test_prevent_loss($board);
			//var_dump($position);
			if ($position == NULL) {
				//var_dump($position);
				if (isset($blank_spaces)) {
					$blank_space_count = sizeof($blank_spaces);
					$rand_chooser = rand(0, $blank_space_count-1);
					//var_dump($blank_spaces[$rand_chooser]);
					//echo $rand_chooser;
					$board->addPiece($blank_spaces[$rand_chooser][0], $blank_spaces[$rand_chooser][1], AI::PIECE);
					$position = $blank_spaces[$rand_chooser];
				}
			}
		}
		else { exit(1); }
        return $position;
    }
}

function testForExistingPiece($board, $position) {
	$valid = !$board->hasPieceAtPosition($position[0], $position[1]);
	if (!$valid) {
		//echo "Piece already at this position\n";
	}
	return $valid;
}

/**
 * @throws Exception If the AI does not prevent the player from winning
 */
function test_prevent_loss($board)
{
	$position = test_go_for_win($board, true);
	return $position;
}

/**
 * @throws Exception If the AI does not go for the win
 */
function test_go_for_win($board, $isBlocking)
{
	if ($isBlocking == true)
		$pieceGet = Player::PIECE;
	else
		$pieceGet = AI::PIECE;
	$position = NULL;
	$blank_spaces = $board->getBlankPositions($board);
	$movePos = new stdclass;
	foreach ($blank_spaces as $blank_space) {
		$movePos->row = $blank_space[0];
		$movePos->column = $blank_space[1];
		$ai_pieces = 0;
		//attempt win at row
		for ($i = 0; $i < 3; $i++) {
			if ($board->getPieceAtPosition($movePos->row, $i) == $pieceGet) {
				$ai_pieces++;
			}
			if ($ai_pieces > 1) {
				for ($j = 0; $j < 3; $j++) {
					if ($board->getPieceAtPosition($movePos->row, $j) == '') {
						$board->addPiece($movePos->row, $j, AI::PIECE);
						echo "\nwinning move in row: ".$movePos->row." ".$j."\n";
						echo 'Winner AI';
						$actual = (string)$board;
						echo "\nWinners Board:\n", debug_board($actual), "\n";
						$position = true;
						break;
					}
				}
			}
		}
		$ai_pieces = 0;
		//attempt win at column
		for ($k = 0; $k < 3; $k++) {
			if ($board->getPieceAtPosition($k, $movePos->column) == $pieceGet) {
				$ai_pieces++;
			}
			if ($ai_pieces > 1) {
				for ($l = 0; $l < 3; $l++) {
					if ($board->getPieceAtPosition($l, $movePos->column) == '') {
						$board->addPiece($l, $movePos->column, AI::PIECE);
						echo "\nwinning move in col: ".$l." ".$movePos->column."\n";
						echo 'Winner AI';
						$actual = (string)$board;
						echo "\nWinners Board:\n", debug_board($actual), "\n";
						$position = true;
						break;
					}
				}
			}
		}
		$ai_pieces = 0;
		//diag1 (AI)
		if ($movePos->column == $movePos->row) {
			//we're on a diagonal
			for ($m = 0; $m < 3; $m++) {
				if ($board->getPieceAtPosition($m, $m) == $pieceGet) {
					$ai_pieces++;
				}
				if ($ai_pieces > 1) {
					for ($n = 0; $n < 3; $n++) {
						if ($board->getPieceAtPosition($n, $n) == '') {
							$board->addPiece($n, $n, AI::PIECE);
							echo "\nwinning move in diag: ".$n." ".$n."\n";
							echo 'Winner AI';
							$actual = (string)$board;
							echo "\nWinners Board:\n", debug_board($actual), "\n";
							$position = true;
							break;
						}
					}
				}
			}
		}
		$ai_pieces = 0;
		//diag2 (AI)
		if ($movePos->column + $movePos->row == 3-1) {
			//we're on another diagonal
			for ($o = 0; $o < 3; $o++) {
				if ($board->getPieceAtPosition($o, (3 - 1)-$o) == $pieceGet) {
					$ai_pieces++;
				}
				if ($ai_pieces > 1) {
					for ($p = 0; $p < 3; $p++) {
						if ($board->getPieceAtPosition($p, (3 - 1)-$p) == '') {
							$board->addPiece($p, (3 - 1)-$p, AI::PIECE);
							echo "\nwinning move in alt diag: ".$p." ".((3 - 1)-$p)."\n";
							echo 'Winner AI';
							$actual = (string)$board;
							echo "\nWinners Board:\n", debug_board($actual), "\n";
							$position = true;
							break;
						}
					}
				}
			}
		}
		if ($position == true) { break; }
	}
	return $position;
}

function debug_board($board)
{
    return str_replace("\n", " NEW LINE\n", $board);
}

/**
 * The board should be displayed in a grid.
 * A blank space: -
 * A piece: X or O
 *
 * Three columns in three rows
 *
 * Examples:
 * space: [row, column, X or O]
 * where data is a list of spaces
 *
 * data: [ [0, 0, O], [1, 0, O], [2, 0, O] ]
 * O--
 * O--
 * O--
 *
 * data: [ [1, 1, X] ]
 * ---
 * -X-
 * ---
 *
 * @throws Exception If board is rendered incorrectly.
 */
function test_display_board()
{
    $board = new Board;

    $board->addPiece(0, 0, 'O');
    $board->addPiece(1, 1, 'O');
    $board->addPiece(2, 2, 'O');

    $actual = (string)$board;

    $expected = "O--\n-O-\n--O\n";

    if ($actual !== $expected) {
        echo "Expected:\n", debug_board($expected);
        echo "\nActual:\n", debug_board($actual), "\n";
        throw new \Exception('test_display_board: Board should render correctly');
    }
}

/**
 * @throws Exception When a winning piece combination does not trigger a win
 */
function test_player_wins()
{
    $board = new Board;
	$player = new Player;
    $board->addPiece(1, 0, Player::PIECE);
    $board->addPiece(1, 1, Player::PIECE);
    //$board->addPiece(0, 2, Player::PIECE);
	$movePos = $player->placePiece($board);
	//var_dump($movePos);
    if ($board->getWinner($board, $movePos) !== Player::PIECE) {
        throw new \Exception("Player should be able to win");
    }
}

function run_tests()
{
    //test_display_board();
    //test_player_wins();
	init();
    //test_go_for_win();
    //test_prevent_loss();
}

try {
    run_tests();
} catch (\Exception $e) {
    echo "Tests failed, game aborted\nMessage: ", $e->getMessage(), "\n";
    exit(1);
}

$board  = new Board;
$ai     = new AI;
$player = new Player;


/*
 * TODO Game Loop
 *
 * The game should be running if
 * 1. Nobody has won
 * 2. And the board is not full
 *
 * The AI goes first
 *
 * After the AI places a piece, display the board using the __toString method
 *
 * After someone places a piece, check if they won.
 *
 * If someone won display the message and exit
 *
 * If the board is full, display a message and exit
 */
function init() {
	$board = new Board;
	$player = new Player;
	$ai = new AI;
	$movePos = new stdclass;
	while (!empty($board->getBlankPositions($board))) {
		if ($board->getWinner($board, $movePos) == Player::PIECE) {
			echo "\nPlayer Wins!\n";
			$actual = (string)$board;
			echo "\nBoard:\n", debug_board($actual), "\n";
			exit();
		}
		$movePos = $ai->placePiece($board);
		$actual = (string)$board;
		echo "\nBoard:\n", debug_board($actual), "\n";
		if (empty($board->getBlankPositions($board))) {
			echo "Board is full: TIE!";
			exit();
		}
		$movePos = $player->placePiece($board);
	}
}
?>