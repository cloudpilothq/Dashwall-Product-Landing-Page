jQuery(function($){
  function selectSingle(target){var frame=wp.media({title:'Choose product image',button:{text:'Use image'},multiple:false,library:{type:'image'}});frame.on('select',function(){var a=frame.state().get('selection').first().toJSON();$('#'+target).val(a.id);$('#'+target+'_preview').html('<img src="'+a.sizes.thumbnail.url+'" alt="">');});frame.open();}
  $('.mdpl-select-image').on('click',function(){selectSingle($(this).data('target'));});
  $('.mdpl-remove-image').on('click',function(){var t=$(this).data('target');$('#'+t).val('');$('#'+t+'_preview').empty();});
  $('.mdpl-select-gallery').on('click',function(){var frame=wp.media({title:'Select product gallery images',button:{text:'Use selected images'},multiple:true,library:{type:'image'}});frame.on('select',function(){var ids=[],html='';frame.state().get('selection').each(function(a){var x=a.toJSON();ids.push(x.id);html+='<img src="'+x.sizes.thumbnail.url+'" alt="">';});$('#mdpl_gallery').val(ids.join(','));$('#mdpl_gallery_preview').html(html);});frame.open();});
});
