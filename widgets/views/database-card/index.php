<?php

$this->registerCss(<<< CSS
	.app-border:hover {
        cursor: pointer;
        box-shadow: rgb(0 0 0 / 30%) 0px 1px 20px 0 !important;
    }
    .bg-diagonal-light-success:hover {outline: 3px solid #1BC5BD !important;}
    .bg-diagonal-light-primary:hover {outline: 3px solid #3699FF !important;}
    .bg-diagonal-light-info:hover {outline: 3px solid #3699FF !important;}
    .bg-diagonal-light-secondary:hover {outline: 3px solid #E4E6EF !important;}
    .bg-diagonal-light-danger:hover {outline: 3px solid #F64E60 !important;}
    .bg-diagonal-light-warning:hover {outline: 3px solid #FFA800 !important;}
CSS);

$this->registerjs(<<< JS
	$('.database-card').click(function() {
		let url = $(this).data('url');
		window.location.href = url;
	});
JS);
?>

<div class="row">
	<?= $content ?>
</div>