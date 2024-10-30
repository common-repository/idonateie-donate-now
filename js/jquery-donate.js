var templateUrl = (donatevalues.wsiteurl);

var JQ = jQuery.noConflict();
JQ(document).ready(function(){
	
    JQ(".dnpiframe").click(function(){
		var id = JQ(this).attr('ID');
	JQ.ajax({
		type: 'GET',
		url: id,
		success: function( resp ) {
			JQ.dnpcolorbox({href:resp, iframe:true, innerWidth:'1000px', innerHeight:'90%'});
			}
	  });
	   });
	
    JQ("#url_image").click(function(){
		JQ("#image_url").hide();
	   JQ("#image_url").val("");  //hide give image url
		 JQ("#pic").show();
	   });
	 JQ("#url_button").click(function(){
       JQ("#image_url").show();//show image Url
	   JQ("#image_url").val("<?php echo $pic_name_default; ?>");
	   JQ("#pic").val(""); // blank a image
		JQ("#pic").hide();
    });
	
	JQ("#url_image").click(function(){
          JQ('#url_image').attr('checked', true)
    JQ('#url_button').attr('checked', false)
	
    });  
	
	JQ("#url_button").click(function(){
          JQ('#url_button').attr('checked', true)
    JQ('#url_image').attr('checked', false)
	
    });
	

	JQ("#fmgy").click(function(){
          JQ('#fmgy').attr('checked', true)
    JQ('#fmgn').attr('checked', false)
	
    });  
	
	JQ("#fmgn").click(function(){
          JQ('#fmgn').attr('checked', true)
    JQ('#fmgy').attr('checked', false)
	
    });
	
	
	
});

function closeAll(){
	
	document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none';
}