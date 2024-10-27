<?php 
	require_once(__DIR__ . '/../../core/Link.php');
	$postmeta = get_post_meta( get_the_ID(), 'adapti_tags', true );
	$postmeta = $postmeta == "" ? "[]" : $postmeta;
 ?>

<div class="adapti-metabox">
	<input type="hidden" name="adapti_tags" id="adapti_tags">

	<div class="search-bar">
		<input class="adapti-search input" type="text" name="search" placeholder="Add a tag match" autocomplete="off">
		<div class="search-results hide"></div>
	</div>

	<div class="text-light">Applied adapti tags :</div>

	<div class="adapti-tags"></div>	

	<script type="text/javascript" src="<?php echo Adapti_plug_Link::cdnroute('/Common/js/search.js', 'core'); ?>"></script>

	<script type="text/javascript">
		window.onload = function(){
			window.$ = jQuery;

			window.adapti = window.adapti || {};
			window.adapti.tag = window.adapti.tag || {};
			window.adapti.tag.content = JSON.parse(<?php echo json_encode($postmeta); ?>);
			window.adapti.tag.container = $('input#adapti_tags');

			window.adapti.tag.update = function(){
				window.adapti.tag.raw = JSON.stringify(window.adapti.tag.content);
				console.log(window.adapti.tag.container);
				window.adapti.tag.container.val(window.adapti.tag.raw);

				var wrap = $('.adapti-tags');
				wrap.html("");
				window.adapti.tag.content.forEach(function(tag, index){
					var wrapRes = $('<div class="adapti-tag"></div>');
		            wrapRes.html('<span class="type">'+ tag.type +' - '+ tag.category +' :</span><span class="value">'+ tag.value +'</span><span class="close">X</span>');
		            wrapRes.find('.close').on('click', function(e){ window.adapti.tag.remove(index) });
		            wrap.append(wrapRes);
				})
			}
			window.adapti.tag.create = function(data){
				window.adapti.tag.content.push(data);
				window.adapti.tag.update();
			}
			window.adapti.tag.remove = function(index){
				window.adapti.tag.content.splice(index, 1);
				window.adapti.tag.update();
			}

			window.adapti.tag.update();

			$.ajax({
				url: '<?php echo Adapti_plug_Link::apiroute("/api/taglist"); ?>',
                type: 'POST'
			}).done(function(data){
				window.adapti.tag.available = JSON.parse(data);
				window.search = new SearchModule({
					minLetters: 1,
			        container: '.adapti-metabox',
			        handleFind: function (item, content) {
			            var wrap = $('.adapti-tags');

			            var wrapRes = $('<div></div>');
			            wrapRes.html(content);

			            var cat = wrapRes.find('.cat').text();
			            var subcat = wrapRes.find('.subcat').text();
			            var value = wrapRes.find('.value').text();

			            window.adapti.tag.create({ type: cat, category: subcat, value: value });

			            this.bar.val('');
			            this.resultsWrap.addClass('hide');
			        },
			        printResults: function(value){
			            var data = window.adapti.tag.available;
			            var resId = 0;
			            for(var cat in data){
		                    for(var subcat in data[cat]){
	                            if(data[cat][subcat].constructor === Array){
	                                data[cat][subcat].forEach(function(item){
	                                    if(item.toLowerCase().includes(value.toLowerCase())){
	                                        this.newResult(null, item, value, resId, function(content){
	                                            return '<span class="type"><span class="cat">'+cat+'</span> - <span class="subcat">'+subcat+'</span> : </span>'+content;
	                                        })
	                                        resId++;
	                                    }
	                                }.bind(this))
	                            }
		                    }
			            }
			            if(!this.resultsWrap.find('.result').length){
			                res = $('<div>').addClass('result error').html('No match.').css('max-height', '60px');
			                this.resultsWrap.append(res);
			            }
			        },
			        toggleBar: function(e){
			            this.bar.focus();
			        }
			    });
			})
		}
	</script>
</div>