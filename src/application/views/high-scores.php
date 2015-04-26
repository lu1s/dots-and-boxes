<div class="main">
	<h1>Top <?php echo count($scores); ?> players</h1>

	<?php $i=1; foreach($scores as $score): ?>

		<fieldset class="score">
			<legend class="thenumber"><?php echo $i; ?></legend>
			<div class="theuser"><?php echo $score->username; ?></div>
			<div class="thescore"><?php echo $score->thesum; ?> boxes</div>
			<div class="clear"></div>
		</fieldset>	

	<?php $i++; endforeach; ?>

</div>