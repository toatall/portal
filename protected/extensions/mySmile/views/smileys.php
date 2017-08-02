<style>
	#smile-box-<?= $prefix ?> {
		background: url('/css/images/smile_icon.png') no-repeat;
		width: 17px;
		height: 17px;
		cursor: pointer;
	}
	#smile-content-<?= $prefix ?> a:hover {
		background: #aaa;
	}
	#smile-content-<?= $prefix ?> {
		background: while;
		width:300px; 
		position: absolute; 
		margin-top: 10px;		
		display: none;
		padding: 5px;
	}
	
	.uneditable-input, .uneditable-textarea {
		cursor: auto;
		color: #555;	
	}
</style>

<div id="smile-box-<?= $prefix ?>" style="position: relative;">
	<div id="smile-content-<?= $prefix ?>" class="<?php echo $containerCssClass;?> well">	
		<?php 
			for ($i=0; $i<32;$i++)
			{
		?>
			<a href="javascript:;" onclick="$('#<?= $textareaId ?>').focus(); pasteHtmlAtCaret(<?= ($i+1) ?>);">
				<img src="/css/images/blank.gif" class="smile-sm-<?= ($i+1) ?>" /></a>
		<?php 
			}
		
		?>
	</div>
</div>
<script type="text/javascript">
	
	function pasteHtmlAtCaret(num) {
		var html = '<img src="/css/images/blank.gif" class="smile-sm-' + num + '" />';
		var sel, range;
		if (window.getSelection) {
			// IE9 and non-IE
			sel = window.getSelection();
			if (sel.getRangeAt && sel.rangeCount) {
				range = sel.getRangeAt(0);
				range.deleteContents();
				// Range.createContextualFragment() would be useful here but is
				// non-standard and not supported in all browsers (IE9, for one)
				var el = document.createElement("div");
				el.innerHTML = html;
				var frag = document.createDocumentFragment(), node, lastNode;
				while ( (node = el.firstChild) ) {
					lastNode = frag.appendChild(node);
				}
				range.insertNode(frag);
			
				// Preserve the selection
				if (lastNode) {
					range = range.cloneRange();
					range.setStartAfter(lastNode);
					range.collapse(true);
					sel.removeAllRanges();
					sel.addRange(range);
				}
			}
		} else if (document.selection && document.selection.type != "Control") {
			// IE < 9
			document.selection.createRange().pasteHTML(html);
		}
	}
		
	$(function(){
		$('#smile-box-<?= $prefix ?>').hover(
			function() {
				$(this).find('#smile-content-<?= $prefix ?>').fadeIn();  
			},
			function() {
				$(this).find('#smile-content-<?= $prefix ?>').fadeOut();				
			}
		);
	})
	
</script>