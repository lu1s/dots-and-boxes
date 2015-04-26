	<div class="clear"></div>

	</div>
    <div id="footer">
	<div class="left copyright">
		&copy; <script>document.write((new Date()).getFullYear());</script>
		Dots&amp;Boxes. All rights reserverd.
	</div>
	<div class="right social">
		<a href="#"> <img src="<?php echo base_url('img/social-fb.png'); ?>" alt="Facebook" /> </a>
		<a href="#"> <img src="<?php echo base_url('img/social-tw.png'); ?>" alt="Twitter" /> </a>

	</div>
	<div class="clear"></div>
    </div>

		<?php if(isset($foot_jsvars)): ?>
			<script type="text/javascript">
				<?php foreach($foot_jsvars as $var): ?>
						var <?php echo $var['name']; ?> = <?php echo json_encode($var['data']); ?>;
				<?php endforeach; ?>
			</script>
		<?php endif; ?>

 	 	<?php if(isset($foot_jsfiles)): foreach($foot_jsfiles as $file): ?>

 	 		<script type="text/javascript" src="<?php echo $file; ?>"></script>

 		<?php endforeach; endif; ?>
</body>
<html>