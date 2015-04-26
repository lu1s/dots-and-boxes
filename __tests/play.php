<html><head><title>3x3 Dots-and-Boxes Game</title>
<meta name="robots" content="nofollow"></head><body>
<h1 align=center>3x3 Dots-and-Boxes Game</h1>
<?php
$moves = $_REQUEST['moves'];
$opt = $_REQUEST['opt'];
if($opt == "") $opt = "NB";  // Don't show analysis; Computer is player B
$bShow = substr($opt,0,1) == "Y";
$sComputerPlay = substr($opt,1,1);

list($usec,$sec)=explode(" ",microtime());
$seed = round($sec * $usec);
srand($seed);

$tmpfile = tempnam("/tmp", "boxes");

do {
    $fp = fopen($tmpfile, "w");
    if ( ! $fp ) {
        exit("<b>Unable to create temporary file for the play program</b>\n");
    }
    $left = $moves;
    while(strlen($left) >= 2) {
        fputs($fp,substr($left,0,2)."\n");
        $left = substr($left,2);
    }
    fputs($fp,".\n");
    fclose($fp);
    
    $fp = popen( "./playL 3x3 <$tmpfile 2>&1", "r");
    if ( ! $fp ) {
        unlink($tmpfile);
        exit("<b>Unable to run the play program</b>\n");
    }
    
    $n = 0;
    while ( ($line = fgets($fp, 1024)) != FALSE ) {
        $output[$n++] = substr($line,1);
        if($line == "   a  b  c  d  e  f  g\n") $n = 0;
    }
    pclose($fp);
    unlink($tmpfile);
    
    $output[14] = "\n";
    $output[15] = "E".substr($output[15],0,22)." or choose one of the following.";
    $letters = "abcdefghijklmnopqrstuvwxyz";
    
    $nMin = 99;
    $nMax = -99;
    $nScoreA = 0;
    $nScoreB = 0;
    for($i=0;$i<7;$i++) {
        $line = $output[2*$i+1];
        for($j=6;$j>=0;$j--) {
            $mid = 3*$j+2;
            $ch = substr($line,$mid,1);
            $scores[$i][$j] = 99;
            if($ch >= '0' && $ch <= '9') {
                sscanf(substr($line,$mid-1,2),"%d",$score);
                $nMin = min($nMin,$score);
                $nMax = max($nMax,$score);
                $scores[$i][$j] = $score;
                $line = substr($line,0,$mid-1)."<a href=play.php?moves=".
                 $moves.substr($letters,$i,1).substr($letters,$j,1).
                 "&opt=".$opt.">".($bShow?substr($line,$mid-1,3):" ? ")."</a>".
                 substr($line,$mid+2);
            } elseif($ch == ' ' && (($i%2)^($j%2))) {
                $line = substr($line,0,$mid-1)."<a href=play.php?moves=".
                 $moves.substr($letters,$i,1).substr($letters,$j,1).
                 "&opt=".$opt."> ? </a>".substr($line,$mid+2);
            } elseif($ch == 'A') $nScoreA++;
            elseif($ch == 'B') $nScoreB++;
        }
        $output[2*$i+1] = $line;
    }
    
    $bRepeat = false;
    if($nMin == 99) {  // game is over
        $output[15] = "<b>Player ".($nScoreB >= 6 ? "B" : "A")." has won.</b>";
    } elseif($sComputerPlay == substr($output[15],22,1)) {
        // it's our turn to move
        $nTarget = $sComputerPlay == 'A' ? $nMax : $nMin;
        $nChoices = 0;
        for($i=0;$i<7;$i++) {
            for($j=0;$j<7;$j++) {
                if($scores[$i][$j] == $nTarget) {
                    $ii[$nChoices] = $i;
                    $jj[$nChoices] = $j;
                    $nChoices++;
                }
            }
        }
        $nChoice = rand(0,$nChoices-1);
        $moves .= substr($letters,$ii[$nChoice],1).
         substr($letters,$jj[$nChoice],1);
        $bRepeat = true;
    }
} while($bRepeat);

echo "Player A has $nScoreA boxes. ".
 "Player A (first player) needs 4 boxes to win.<br>".
 "Player B has $nScoreB boxes. ".
 "Player B (second player) needs 6 boxes to win.<br>\n";
echo "See <a href=http://www.cae.wisc.edu/~dwilson/boxes/results.shtml>".
 "Analysis Results</a> for why player B ".
 "needs 6 rather than 5 boxes to win.<p>";
echo "<pre>";
for($i=0;$i<$n;$i++){
    if(substr($output[$i],0,1) == ' ') {
        echo substr($output[$i],1);
    } else {
        echo $output[$i];
    }
}
echo "</pre>\n";
if($nMin < 99) {
    echo "<tt> ".($sComputerPlay == 'B' ? 'X' : '&nbsp;').
     " </tt><a href=play.php?moves=$moves&opt=".substr($opt,0,1).
     "B>Make me player A.</a><br>\n";
    echo "<tt> ".($sComputerPlay == 'A' ? 'X' : '&nbsp;').
     " </tt><a href=play.php?moves=$moves&opt=".substr($opt,0,1).
     "A>Make me player B.</a><br>\n";
    echo "<tt> ".($sComputerPlay == 'C' ? 'X' : '&nbsp;').
     " </tt><a href=play.php?moves=$moves&opt=".substr($opt,0,1).
     "C>Let me move for both players.</a><p>\n";
?>  <a href=play.php?moves=<?php echo "$moves&opt=".($bShow?"N":"Y").
     substr($opt,1,1).">".($bShow?"Hide":"Show"); ?> Analysis</a><p>
<?php } ?>
<a href=http://www.cae.wisc.edu/~dwilson/boxes/>Dots-and-Boxes Index</a><p>
<?php if($moves != "") { 
      echo "<a href=play.php?opt=$opt>Start a new game</a><p>\n";
      echo "Use your browser's back button to back up a move.<p>\n";
}
if($bShow && $nMin < 99) { ?>
In the analysis, the scores are the number of boxes player A will get minus
the number of boxes player B will get with best play if the player on the move
selects the corresponding line.  -1, 1, 3, 5, 7 and 9 are wins for the
first player to move (player A);
-3, -5, -7 and -9 are wins for the second player to move (player B).
A suffix of<tt> v </tt>on a score means player A must make the
next loony move;<tt> ^ </tt>means player B must.
<?php } ?>
</body></html>