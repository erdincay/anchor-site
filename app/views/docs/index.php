<?php echo $header; ?>

	<section class="wrap">
		<?php echo $sidebar; ?>

		<section class="content">
			<?php echo $content; ?>
		</section>
	</section>

	<script src="<?php echo asset('assets/js/rainbow-custom.min.js'); ?>"></script>
	<script>
		(function() {
			var elements = document.querySelectorAll('pre code');

			for(var i = 0; i < elements.length; i++) {
				elements[i].setAttribute('data-language', 'php');
			};

			Rainbow.color();
		}());
	</script>

<?php echo $footer; ?>