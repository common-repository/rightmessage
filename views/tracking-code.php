<?php
// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}
?>
<!-- RightMessage WP embed -->
<script type="text/javascript"> 
(function(p, a, n, d, o, b) {
    o = n.createElement('script'); o.type = 'text/javascript'; o.async = true; o.src = 'https://tw.rightmessage.com/'+p+'.js';
    b = n.getElementsByTagName('script')[0]; b.parentNode.insertBefore(o, b);
    d = function(h, u, i) { var o = n.createElement('style'); o.id = 'rmcloak'+i; o.type = 'text/css';
        o.appendChild(n.createTextNode('.rmcloak'+h+'{visibility:hidden}.rmcloak'+u+'{display:none}'));
        b.parentNode.insertBefore(o, b); return o; }; o = d('', '-hidden', ''); d('-stay-invisible', '-stay-hidden', '-stay');
    setTimeout(function() { o.parentNode && o.parentNode.removeChild(o); }, a);
})('<?php echo esc_js($account_id); ?>', 20000, document);
</script>
