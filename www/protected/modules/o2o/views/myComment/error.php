<script type="text/javascript">
document.title = '错误';
var message = <?php echo json_encode($msg); ?>;
$.valert(message);
</script>