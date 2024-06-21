<div class="toc-container" id="toc-container">
    <div class="toc-header">
        <p id="toc-header-title">{!! trans('toc.post.title') !!}</p>
        <div class="toc-show"><span class="fas fa-angle-up"></span></div>
    </div>
    <ol id="toc"></ol>
</div>
<script defer>
	$(function () {
		$("#toc").toc({content:"div{!! $contentId !!}", headings:"{!! $heading !!}"});

		$(document).on('click', '#toc li a', function () {
			let id = $(this).attr('href');
			$('html, body').animate({
				scrollTop: $(id).offset().top - 100
			}, 1000);
			return false;
		});

		$(document).on('click', '#toc-container .toc-show', function () {
			$('#toc-container #toc').toggle();
			return false;
		});
	})
</script>