<!DOCTYPE html>
<html>
<head>
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/touch_punch.js"></script>
	<script src="js/glide.js"></script>
	<script src="js/jquery.colorPicker.js"></script>
	<link rel="stylesheet" href="css/glide.core.css">
    <link rel="stylesheet" href="css/glide.theme.css">
	<link rel="stylesheet" href="css/colorPicker.css" type="text/css" />
	<title>Erintuitive's Psychic Place</title>
</head>
<body style="background-color: #FFBB22;">
	<script>
	  window.fbAsyncInit = function() {
		FB.init({
		  appId            : '817064305070781',
		  autoLogAppEvents : true,
		  xfbml            : true,
		  version          : 'v2.9'
		});
		FB.AppEvents.logPageView();
	  };
	  (function(d, s, id){
		 var js, fjs = d.getElementsByTagName(s)[0];
		 if (d.getElementById(id)) {return;}
		 js = d.createElement(s); js.id = id;
		 js.src = "//connect.facebook.net/en_US/sdk.js";
		 fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));
	   FB.getLoginStatus(function(response) {
	   if (response.status === 'connected') {
			//insert stuff here
		  }
		  else {
			FB.login();
		  }
		});
	</script>
	<table width="740px">
	<td>
		<tr>
			<td width="10%">
				<div id="avatarOptions" class="tops">
					Please wait while your avatar loads.
				</div>
			</td>
			<td id="itemPreview" width="10%">
				
			</td>
			<td id="result" width="500px" style="border-style: dashed; border-width: 6px;">
				
				<div id="relativeContainer" style="position: relative; margin-left: 4px; margin-top:50px; " width="86px" height="380px">
				<?php if(isset($_POST['getAvatar']) && isset($_POST['me_id'])){
					$me_id = $_POST['me_id']; 
					$avatar = $_POST['getAvatar'];
					//10155567524149846 my user id
				} ?>
				<div id="top"></div>
				<div id="bottom"></div>
				</div>
			</td>
		</tr>
	</table>
	<div id="buttons">
		
	</div>
	<script>
		var previous = '';
		function shuffle(array) {
			var rand, index = -1,
				length = array.length,
				result = Array(length);
			while (++index < length) {
				rand = Math.floor(Math.random() * (index + 1));
				result[index] = result[rand];
				result[rand] = array[index];
			}
			return result;
		}
		var num = 0;
		var me_id = "<? echo $me_id; ?>";
		var count1 = 0;
		var count2 = 0;
		var countPresent = 0;
		$.get("https://api.myjson.com/bins/vzecj", function (data3, textStatus3, jqXHR3) {
			for(i=0; i<data3['person'].length; i++){
				if(data3['person'][i]['user_id'] != me_id){
					count1 += 1;
				}
				else {
					count2 += 1;
					countPresent = i;
				}
			}
			if(count1 == 0 && count2 > 0){
				$.ajax({
					url:"https://api.myjson.com/bins/vzecj",
					type:"PUT",
					data:'{"person": [{"user_id":"<? echo $me_id; ?>", "name":"guest", "avatar":"<? echo $avatar; ?>", "pos_x":-1, "pos_y":-1}]}',
					contentType:"application/json; charset=utf-8",
					dataType:"json",
					success: function(data, textStatus, jqXHR){
						$.get("https://api.myjson.com/bins/vzecj", function (data, textStatus, jqXHR) {
							var avatarJSON = data['person'][0]['avatar'];
							$.post("convertAvatar.php", {convert: avatarJSON}, function(data2){
								document.getElementById("relativeContainer").innerHTML = data2;
								previous = relativeContainer.innerHTML;
								document.getElementById("itemPreview").innerHTML = '<button id="randomTop" onclick="randomizeTop()">Randomize top</button><br><button id="randomTop" onclick="randomizeBottom()">Randomize bottom</button><br><div style="padding:50px;"><label for="color1">Top color</label> <input id="color1" type="text" name="color1" value="#333399" onchange="makeTopColor()"/></div><br><div style="padding:50px;"><label for="color2">Sleeve color</label> <input id="color2" type="text" name="color2" value="#333399" onchange="makeSleeveColor()"/></div>';
								document.getElementById("buttons").innerHTML = '<button id="next" onclick="nextOptions()">Next</button>';
							});
						});
					}
				});
			}
			else if(count1 > 0 && count2 == 0){
				var pushThis = {
					"user_id": me_id,
					"name":"guest",
					"avatar":"<? echo $avatar; ?>",
					"pos_x":-1,
					"pos_y":-1
				};
				data3['person'].push(pushThis);
				var len = data3['person'].length;
				document.getElementById("relativeContainer").innerHTML = "ok";
				$.ajax({
					url:"https://api.myjson.com/bins/vzecj",
					type:"PUT",
					data: JSON.stringify(data3),
					contentType:"application/json; charset=utf-8",
					dataType:"json",
					success: function(data, textStatus, jqXHR){
						$.get("https://api.myjson.com/bins/vzecj", function (data, textStatus, jqXHR) {
							var avatarJSON = data['person'][len-1]['avatar'];
							$.post("convertAvatar.php", {convert: avatarJSON}, function(data2){
								document.getElementById("relativeContainer").innerHTML = data2;
								previous = relativeContainer.innerHTML;
								document.getElementById("itemPreview").innerHTML = '<button id="randomTop" onclick="randomizeTop()">Randomize top</button><br><button id="randomTop" onclick="randomizeBottom()">Randomize bottom</button><br><div style="padding:50px;"><label for="color1">Top color</label> <input id="color1" type="text" name="color1" value="#333399" onchange="makeTopColor()"/></div><br><div style="padding:50px;"><label for="color2">Sleeve color</label> <input id="color2" type="text" name="color2" value="#333399" onchange="makeSleeveColor()"/></div>';
								document.getElementById("buttons").innerHTML = '<button id="next" onclick="nextOptions()">Next</button>';
							});
						});
					}
				});
			}
			else if(count1 > 0 && count2 > 0){
				for(i=0; i<data3['person'].length; i++){
					if(data3['person'][i]['user_id']==me_id){
						data3['person'][i]['avatar'] = "<? echo $avatar; ?>";
						$.ajax({
							url:"https://api.myjson.com/bins/vzecj",
							type:"PUT",
							data: JSON.stringify(data3),
							contentType:"application/json; charset=utf-8",
							dataType:"json",
							success: function(data, textStatus, jqXHR){
								$.get("https://api.myjson.com/bins/vzecj", function (data, textStatus, jqXHR) {
									var avatarJSON = data['person'][i]['avatar'];
									$.post("convertAvatar.php", {convert: avatarJSON}, function(data2){
										document.getElementById("relativeContainer").innerHTML = data2;
										previous = relativeContainer.innerHTML;
										document.getElementById("itemPreview").innerHTML = '<button id="randomTop" onclick="randomizeTop()">Randomize top</button><br><button id="randomTop" onclick="randomizeBottom()">Randomize bottom</button><br><div style="padding:50px;"><label for="color1">Top color</label> <input id="color1" type="text" name="color1" value="#333399" onchange="makeTopColor()"/></div><br><div style="padding:50px;"><label for="color2">Sleeve color</label> <input id="color2" type="text" name="color2" value="#333399" onchange="makeSleeveColor()"/></div>';
										document.getElementById("buttons").innerHTML = '<button id="next" onclick="nextOptions()">Next</button>';
									});
								});
							}
						});
						break;
					}
				}
				 
			}
		});
	function getRandomColor() {
		var letters = '0123456789ABCDEF';
		var color = '#';
		for (var i = 0; i < 6; i++) {
			color += letters[Math.floor(Math.random() * 16)];
		}
		return color;
	}
	var shirtStuff = document.getElementById("shirtStuff");
	var relativeContainer = document.getElementById("relativeContainer");
	var itemPreview = document.getElementById("itemPreview");
	var avatarOptions = document.getElementById("avatarOptions");
	if(document.getElementById("relativeContainer").innerHTML != ''){
		document.getElementById("avatarOptions").innerHTML = "Press Randomize until you find the perfect outfit for your avatar.";
	}
	function randomizeTop() {
		$("#relativeContainer button").show();
		if($(".man")[0]){
			
		}
		else if ($(".woman")[0]){
			document.getElementById("top").innerHTML = femaleTopOverlays()[0];
			$(".shirt").find("path, polygon").attr("fill", getRandomColor());
			$(".shirt").find("path, polygon").css({"fill": getRandomColor()});
			var randColor = getRandomColor();
			$(".sleeves").find("path, polygon").attr("fill", randColor);
			$(".sleeves").find("path, polygon").css({"fill": randColor});
			$("#relativeContainer .shirt").css({'position':'absolute', 'top':'0', 'left':'0', 'margin-top':'0'});
			$("#relativeContainer .shirtOverlay").css({'position':'absolute', 'top':'0', 'left':'0', 'margin-top':'0'});
		}
	}
	function femaleTopOverlays(){
		
	}
	function femaleTopOverlays(){
		var tops1 = '<svg class="shirt" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" class="skin" fill="black" style="stroke-width: 0px; stroke: none;" class="clothes" /></defs><path d="M 219.13 783.615 C 222.463 777.523 239.555 745.442 233.907 737.92 C 228.258 730.398 212.084 703.513 211.008 673.349 C 209.926 643.185 277.459 693.922 281.117 693.926 C 284.784 693.931 258.992 736.045 261.28 738.984 C 263.572 741.925 262.749 737.857 265.04 751.978 C 267.337 766.099 266.946 763.322 266.977 766.097 C 267.008 768.872 279.799 770.012 278.184 780.825 C 264.275 792.601 277.565 817.622 258.637 806.541 C 239.706 795.46 258.609 810.393 219.13 783.615 Z" id="path-1" class="skin" style="stroke: none; stroke-width: 0px; fill: rgb(255, 0, 0);" transform="matrix(-0.999542, 0.030274, -0.030274, -0.999542, 514.654955, 1465.287216)" class="clothes" /></svg>';
		var tops2 = '<svg class="shirt" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" class="skin" fill="black" style="stroke-width: 0px; stroke: none;" class="clothes" /></defs><path d="M 219.708 687.434 C 222.637 693.526 237.647 725.607 232.687 733.129 C 227.728 740.651 210.468 761.085 215.629 772.578 C 220.791 784.072 269.579 773.053 272.796 773.049 C 276.015 773.044 254.719 735.004 256.731 732.065 C 258.743 729.124 258.018 733.192 260.034 719.071 C 262.05 704.95 261.705 707.727 261.732 704.952 C 261.759 702.177 264.51 701.716 268.182 695.995 C 276.335 685.238 254.397 667.684 247.277 690.986 C 240.157 714.288 254.383 660.656 219.708 687.434 Z" id="path-1" class="skin" style="stroke: none; stroke-width: 0px; fill: rgb(255, 0, 0);" class="clothes" /></svg>';
		var tops3 = '<svg class="shirt" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" class="skin" fill="black" style="stroke-width: 0px; stroke: none;" class="clothes" /></defs><path d="M 219.708 687.434 C 222.637 693.526 237.647 725.607 232.687 733.129 C 227.728 740.651 210.468 761.085 215.629 772.578 C 220.791 784.072 269.579 773.053 272.796 773.049 C 276.015 773.044 254.719 735.004 256.731 732.065 C 258.743 729.124 258.018 733.192 260.034 719.071 C 262.05 704.95 261.705 707.727 261.732 704.952 C 261.759 702.177 264.51 701.716 268.182 695.995 C 276.335 685.238 249.984 657.5 244.901 722.558 C 239.818 787.616 255.062 653.866 219.708 687.434 Z" id="path-1" class="skin" style="stroke: none; stroke-width: 0px; fill: rgb(255, 0, 0);" class="clothes" /></svg>';
		var tops4 = '<svg class="shirt" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" class="skin" fill="black" style="stroke-width: 0px; stroke: none;" class="clothes" /></defs><path d="M 219.708 687.434 C 222.637 693.526 237.647 725.607 232.687 733.129 C 227.728 740.651 213.523 767.536 212.574 797.7 C 211.625 827.864 270.937 777.127 274.154 777.123 C 277.373 777.118 254.719 735.004 256.731 732.065 C 258.743 729.124 258.018 733.192 260.034 719.071 C 262.05 704.95 261.705 707.727 261.732 704.952 C 261.759 702.177 272.996 701.037 271.576 690.224 C 259.36 678.448 271.032 653.427 254.406 664.508 C 237.78 675.589 254.383 660.656 219.708 687.434 Z" id="path-1" class="skin" style="stroke: none; stroke-width: 0px; fill: rgb(255, 0, 0);" class="clothes" /></svg>';
		var tops5 = '<svg class="shirt" xmlns="http://www.w3.org/2000/svg" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMax" xmlns:xlink="http://www.w3.org/1999/xlink"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" fill="black" style="stroke-width: 0px; stroke: none;" class="clothes" /></defs><path d="M231.51259393835142,657.8595841129164c12.2541186535191,-3.669447789489709,15.749501702100503,-3.1539934285680147,28.86681758008524,-0.6873545778818198c17.24114718975511,22.398490190909342,24.267617462317162,28.969019474366746,24.055673082761018,28.17955375378483c1.8499712763288017,6.770891684329968,-9.503539340620478,39.3768337497595,-8.247645636353923,32.30330704321182c-5.617158482017317,-0.20047452026381052,-12.913781502269671,6.275497954175989,-13.746124267578125,3.4365234375c-0.8323427653084536,-2.8389745166759894,0.03002471660397532,-15.197294576822515,1.3746337890625,-13.74609375c1.3446090724585247,1.4512008268225145,-1.6754182592098914,43.64363477218296,-4.811139312131559,30.928762952395346c-7.946857583320366,-0.3433778844769222,-31.146551370871606,2.4362293325948485,-26.80490643433066,-1.3745987613539228c0.9051188433990944,-5.185438531205591,2.9341932989911186,-14.56253202515802,2.7492244573997198,-17.869960089478923c-0.1849688415913988,-3.307428064320902,-6.442546484651217,-10.827097913395733,-6.185760498046875,-10.3095703125c0.25678598660434204,0.5175276008957326,5.392936811721768,13.790003487630202,2.061920166015625,15.1207275390625c-3.3310166457061428,1.3307240514322984,-10.727368421098163,0.16453993974334935,-13.746105425902897,0.6872961983673349c-2.3314317861762675,4.646587570394445,-8.114035596930364,-25.8371671686358,-7.560352093628353,-35.052591608523585c0.5536835033020395,-9.215424439887784,11.114256376385782,-10.76392356938436,21.99376459264829,-31.616001824583577Z" id="e4_area3" fill="red" style="stroke: none; stroke-width: 0px;" class="clothes" /></svg>';
		var tops6 = '<svg class="shirt" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMax" xmlns="http://www.w3.org/2000/svg"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" fill="black" style="stroke-width: 0px; stroke: none;" class="clothes"/></defs><polygon id="e1_polygon" style="stroke-width: 0px; stroke: none;" points="239.76 656.485 228.076 661.983 215.017 677.104 209.519 686.726 218.454 734.15 218.454 732.089 229.451 741.711 228.076 727.277 226.701 708.72 232.2 721.092 234.949 732.776 221.89 755.457 212.955 771.952 211.581 787.76 278.249 791.884 270.002 765.079 256.943 738.274 257.63 727.277 263.129 710.782 262.441 732.089 265.191 743.085 274.126 734.838 280.311 699.098 283.748 688.101 283.06 681.228 259.692 657.86" fill="red" class="clothes"/></svg>';
		var tops7 = '<svg class="shirt" xmlns="http://www.w3.org/2000/svg" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMax" xmlns:xlink="http://www.w3.org/1999/xlink"> <defs id="svgEditorDefs"> <path id="svgEditorClosePathDefs" fill="black" style="stroke-width: 0px; stroke: none;" class="clothes" /> </defs> <polygon id="e1_polygon" style="stroke-width: 0px; stroke: none;" points="234.262 657.86 248.008 698.411 258.318 658.547 280.999 679.853 281.686 686.039 274.813 720.404 277.562 755.457 285.122 763.705 285.122 762.33 268.627 762.33 261.754 731.401 262.441 718.342 262.441 719.03 257.63 733.463 260.379 739.649 273.438 767.141 276.875 780.2 278.249 786.386 228.763 792.571 212.955 792.571 217.079 765.766 233.575 737.587 235.636 726.59 232.2 713.531 227.389 707.346 231.513 723.841 232.2 730.714 224.64 766.454 219.828 765.079 208.144 767.828 208.832 767.828 218.454 748.584 218.454 747.897 219.141 730.027 209.519 689.476 215.705 679.853" fill="red" class="clothes" /></svg>';
		var tops8 = '<svg class="shirt" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMax" xmlns="http://www.w3.org/2000/svg"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" fill="black" style="stroke-width: 0px; stroke: none;" class="clothes"/></defs><polygon id="e1_polygon" style="stroke-width: 0px; stroke: none;" points="234.262 657.86 246.633 660.609 258.318 658.547 280.999 679.853 281.686 686.039 274.813 720.404 277.562 755.457 285.122 763.705 285.122 762.33 268.627 762.33 261.754 731.401 262.441 718.342 262.441 719.03 257.63 733.463 260.379 739.649 273.438 767.141 276.875 780.2 278.249 786.386 228.763 792.571 212.955 792.571 217.079 765.766 233.575 737.587 235.636 726.59 232.2 713.531 227.389 707.346 231.513 723.841 232.2 730.714 224.64 766.454 219.828 765.079 208.144 767.828 208.832 767.828 218.454 748.584 218.454 747.897 219.141 730.027 209.519 689.476 215.705 679.853" fill="red" class="clothes"/></svg>';
		var tops9 = '<svg class="shirt" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMax" xmlns="http://www.w3.org/2000/svg"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" fill="black" style="stroke-width: 0px; stroke: none;" class="clothes"/></defs><polygon id="e1_polygon" style="stroke-width: 0px; stroke: none;" points="232.887 657.172 244.475 660.764 258.318 658.547 280.999 679.853 281.686 686.039 274.813 720.404 277.562 755.457 285.122 763.705 285.122 762.33 269.314 766.454 261.754 731.401 262.441 718.342 262.441 719.03 257.63 733.463 260.379 739.649 274.813 767.828 283.061 776.076 287.872 787.76 289.934 959.587 213.643 943.778 217.079 765.766 233.575 737.587 235.636 726.59 232.2 713.531 227.389 707.346 231.513 723.841 232.2 730.714 224.64 766.454 219.828 765.079 208.144 767.828 208.832 767.828 218.454 748.584 218.454 747.897 219.141 730.027 209.519 689.476 215.705 679.853" fill="red" class="clothes"/></svg>';
		var tops10 = '<svg class="shirt" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMax" xmlns="http://www.w3.org/2000/svg"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" fill="red" style="stroke-width: 0px; stroke: none;"/></defs><polygon class="clothes" id="e1_polygon" style="stroke-width: 0px; stroke: none;" points="210.359 632.026 244.611 653.366 274.672 632.623 264.639 664.123 281.686 686.039 274.813 720.404 277.562 755.457 285.122 763.705 285.122 762.33 269.314 766.454 261.754 731.401 262.441 718.342 262.441 719.03 257.63 733.463 260.379 739.649 274.813 767.828 283.061 776.076 287.872 787.76 248.695 887.42 218.454 804.255 217.079 765.766 233.575 737.587 235.636 726.59 232.2 713.531 227.389 707.346 231.513 723.841 232.2 730.714 224.64 766.454 219.828 765.079 208.144 767.828 208.832 767.828 218.454 748.584 218.454 747.897 219.141 730.027 209.519 689.476 225.327 663.358" fill="red" transform=""/></svg>';
		var tops11 = '<svg class="shirt" xmlns="http://www.w3.org/2000/svg" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMax" xmlns:xlink="http://www.w3.org/1999/xlink"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" fill="red" style="stroke-width: 0px; stroke: none;" class="clothes" /></defs><polygon id="e1_polygon" style="stroke-width: 0px; stroke: none;" points="239.073 683.29 251.445 672.98 270.689 666.107 276.187 674.355 281.686 686.039 274.813 720.404 277.562 755.457 285.122 763.705 285.122 762.33 269.314 766.454 261.754 731.401 262.441 718.342 262.441 719.03 257.63 733.463 260.379 739.649 274.813 767.828 282.373 780.887 289.934 787.76 206.082 798.07 217.079 782.261 217.079 765.766 233.575 737.587 235.636 726.59 232.2 713.531 227.389 707.346 231.513 723.841 232.2 730.714 224.64 766.454 219.828 765.079 208.144 767.828 208.832 767.828 218.454 748.584 218.454 747.897 219.141 730.027 209.519 689.476 221.89 690.85" fill="red" class="clothes" /></svg>';
		var tops12 = '<svg class="shirt" xmlns="http://www.w3.org/2000/svg" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMax" xmlns:xlink="http://www.w3.org/1999/xlink"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" fill="red" style="stroke-width: 0px; stroke: none;" class="clothes" /></defs><polygon id="e1_polygon" style="stroke-width: 0px; stroke: none;" points="239.073 683.29 251.445 672.98 270.689 666.107 276.187 674.355 281.686 686.039 274.813 720.404 277.562 755.457 285.122 763.705 285.122 762.33 269.314 766.454 261.754 731.401 262.441 718.342 262.441 719.03 257.63 733.463 260.379 739.649 274.813 767.828 282.373 780.887 289.934 787.76 206.082 798.07 217.079 782.261 217.079 765.766 233.575 737.587 235.636 726.59 232.2 713.531 227.389 707.346 231.513 723.841 232.2 730.714 224.64 766.454 219.828 765.079 208.144 767.828 208.832 767.828 218.454 748.584 218.454 747.897 219.141 730.027 209.519 689.476 221.89 690.85" fill="red" class="clothes" /><polygon id="e4_polygon" style="stroke-width: 0px; stroke: none;" points="273.438 672.293 273.438 670.918 265.191 698.411 266.565 699.785 267.94 705.284 267.94 710.782 266.565 713.531 261.754 717.655 263.129 734.838 270.689 765.766 287.872 763.705 280.311 754.082 276.187 718.342 282.373 684.664 276.187 674.355" fill="lime" class="clothes" /></svg>';
		var tops13 = '<svg class="shirt" xmlns="http://www.w3.org/2000/svg" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMax" xmlns:xlink="http://www.w3.org/1999/xlink"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" fill="red" style="stroke-width: 0px; stroke: none;" class="clothes" /></defs><polygon id="e1_polygon" style="stroke-width: 0px; stroke: none;" points="239.073 683.29 251.445 672.98 270.689 666.107 276.187 674.355 281.686 686.039 274.813 720.404 277.562 755.457 285.122 763.705 285.122 762.33 269.314 766.454 261.754 731.401 262.441 718.342 262.441 719.03 257.63 733.463 260.379 739.649 274.813 767.828 282.373 780.887 289.934 787.76 206.082 798.07 217.079 782.261 217.079 765.766 233.575 737.587 235.636 726.59 232.2 713.531 227.389 707.346 231.513 723.841 232.2 730.714 224.64 766.454 219.828 765.079 208.144 767.828 208.832 767.828 218.454 748.584 218.454 747.897 219.141 730.027 209.519 689.476 221.89 690.85" fill="red" class="clothes" /><polygon id="e4_polygon" style="stroke-width: 0px; stroke: none;" points="273.438 672.293 273.438 670.918 265.191 698.411 266.565 699.785 267.94 705.284 267.94 710.782 266.565 713.531 261.754 717.655 263.129 734.838 270.689 765.766 287.872 763.705 280.311 754.082 276.187 718.342 282.373 684.664 276.187 674.355" fill="lime" class="clothes" /><polygon id="e5_polygon" style="stroke-width: 0px; stroke: none;" points="221.203 690.163 207.457 688.788 218.454 727.965 218.454 747.209 207.457 769.203 216.392 767.828 229.451 743.085 232.887 727.965" fill="lime" class="clothes" /></svg>';
		var tops14 = '<svg class="shirt" xmlns="http://www.w3.org/2000/svg" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMax" xmlns:xlink="http://www.w3.org/1999/xlink"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" fill="red" style="stroke-width: 0px; stroke: none;" class="clothes" /></defs><polygon id="e1_polygon" style="stroke-width: 0px; stroke: none;" points="239.073 683.29 251.445 672.98 270.689 666.107 276.187 674.355 281.686 686.039 274.813 720.404 277.562 755.457 285.122 763.705 290.621 765.079 269.314 766.454 261.754 731.401 262.441 718.342 262.441 719.03 257.63 733.463 260.379 739.649 274.813 767.828 282.373 780.887 289.934 787.76 206.082 798.07 217.079 782.261 217.079 765.766 233.575 737.587 235.636 726.59 232.2 713.531 227.389 707.346 231.513 723.841 232.2 730.714 224.64 766.454 219.828 765.079 208.144 767.828 208.832 767.828 218.454 748.584 218.454 747.897 219.141 730.027 209.519 689.476 221.89 690.85" fill="red" class="clothes" /><polygon id="e5_polygon" style="stroke-width: 0px; stroke: none;" points="221.203 690.163 207.457 688.788 218.454 727.965 218.454 747.209 207.457 769.203 216.392 767.828 229.451 743.085 232.887 727.965" fill="lime" class="clothes" /></svg>';
		var tops15 = '<svg class="shirt" xmlns="http://www.w3.org/2000/svg" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMid meet"  xmlns:xlink="http://www.w3.org/1999/xlink"><rect id="svgEditorBackground" x="202.71499633789062" y="584.406982421875" width="86.59329986572266" height="380.0480041503906" style="fill: none; stroke: none;" class="clothes" /><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs"  class="skin" fill="black" style="stroke-width: 0px; stroke: none;" class="clothes" /></defs><path d="M 219.708 687.434 C 222.637 693.526 237.647 725.607 232.687 733.129 C 227.728 740.651 210.468 761.085 215.629 772.578 C 220.791 784.072 269.579 773.053 272.796 773.049 C 276.015 773.044 254.719 735.004 256.731 732.065 C 258.743 729.124 258.018 733.192 260.034 719.071 C 262.05 704.95 261.705 707.727 261.732 704.952 C 261.759 702.177 264.51 701.716 268.182 695.995 C 276.335 685.238 254.397 667.684 247.277 690.986 C 240.157 714.288 254.383 660.656 219.708 687.434 Z" id="path-1"  class="skin" style="stroke: none; stroke-width: 0px; fill: rgb(255, 0, 0);" class="clothes" /><polygon id="e1_polygon" style="stroke-width: 0px; stroke: none;" points="207.457 777.462 207.457 776.087 213.643 760.279 215.705 761.654 221.203 756.843 230.138 774.025 224.64 762.341 241.822 758.217 247.321 774.025 243.884 757.53 269.315 758.905 263.129 771.276 267.252 756.843 276.188 761.654 276.187 763.028 283.748 770.589 281.686 781.586 278.249 772.651 274.126 771.963 276.187 777.462 264.503 779.524 260.379 775.4 263.129 772.651 255.568 778.836 243.884 779.524 236.324 780.898 221.203 775.4 212.955 771.276 212.955 774.713 208.832 777.462" fill="fuchsia"  class="clothes" /></svg>';
		var tops16 = '<svg class="shirt" width="86" height="380" viewBox="202.715 584.407 86.5933 380.048" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg"><defs id="svgEditorDefs"><path id="svgEditorClosePathDefs" class="lines" fill="black" style="stroke-width: 0px; stroke: none;"/></defs><path d="M 220.661 688.043 C 223.59 694.135 237.991 726.216 233.031 733.738 C 228.072 741.26 210.812 761.694 215.973 773.187 C 221.135 784.681 269.923 773.662 273.14 773.658 C 276.359 773.653 255.063 735.613 257.075 732.674 C 259.087 729.733 258.362 733.801 260.378 719.68 C 262.394 705.559 262.049 708.336 262.076 705.561 C 262.103 702.786 264.854 702.325 268.526 696.604 C 276.679 685.847 262.658 684.737 250.057 686.113 C 237.456 687.489 243.764 684.409 220.661 688.043 Z" id="path-1" class="skin" style="stroke: none; stroke-width: 0px; fill: rgb(255, 0, 0);"/><path d="M 225.594 662.974 C 225.594 668.686 221.94 675.979 221.94 683.073 C 221.94 687.346 224.985 696.042 224.985 693.427" style="stroke: rgb(0, 0, 0); stroke-opacity: 0; fill: rgb(254, 0, 0);"/><path d="M 268.837 688.555 C 270.992 688.555 270.055 682.91 270.055 681.143 C 270.055 676.552 268.461 662.366 263.964 662.366" style="stroke: rgb(0, 0, 0); stroke-opacity: 0; fill: rgb(254, 0, 0);"/></svg>';
		var femaleTops = [tops1, tops2, tops3, tops4, tops5, tops6, tops7, tops8, tops9, tops10, tops11, tops12, tops13, tops14, tops15, tops16];
		var numbers = [22, 16, 27, 24, 13, 20, 25, 22, 20, 20, 15, 16, 15, 15, 14, 3];
		for (k=0; k<16; k++){
			for (i=1; i<=numbers[k]; i++){
				femaleTops.push(femaleTops[k]+'<img class="shirtOverlay" src="svg/human/humanClothes/female_shirt'+(k+1).toString()+'_stickers/female_shirt'+(k+1).toString()+'_sticker'+i.toString()+'.svg">');
			}
		}
		return shuffle(femaleTops);
	}
	</script>
	<style>
		body {
			background-color: #FFBB22;
		}
		#itemPreview button {
			margin:0;
			padding:0;
			border:0;
			background:transparent;
		}
		#avatarOptions {
			position: relative;
			float: left;
		}
		#buttons {
			position: fixed;
			top: 435px;
			left: 40%;
		}
		#itemPreview {
			vertical-align: text-top;
			width: 50%;
			padding: 4px;
			border-width: 5px;
			border-style: solid;
			position: relative;
			padding-left: 50px;
			height: 420px;
		}
		#avatarOptions {
			height: 450px;
		}
		.eyes {
			position: relative;
			margin-top: -310px;
		}
		#chooseSkin {
			position: fixed;
			top: 360px;
		}
		#relativeContainer button {
			margin:0;
			padding:0;
			border:0;
			background:transparent;
		}
	</style>
</body>
</html>