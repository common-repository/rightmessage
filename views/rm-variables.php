<?php
// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}
?>
<!-- RightMessage WP -->
<script type="text/javascript">
	<?php if(isset($rmpanda_cmsdata)) { ?>
	window.rmpanda = window.rmpanda || {};
	window.rmpanda.cmsdata = <?php echo wp_json_encode($rmpanda_cmsdata); ?>;
	<?php } ?>
</script>