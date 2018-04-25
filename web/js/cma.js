var MAX_W = 636;
var MAX_H = 1000;
var canvas;

function addLocalBackground(files) {

	console.log('Loading "' + files[0].name + '"...');

	var reader = new FileReader();
	reader.onload = function() {
		var img = new Image();

		img.onload = function () {
			var imgInstance = new fabric.Image(img);
			var w = imgInstance.width;
			var h = imgInstance.height;
			if (w > MAX_W) {
				h = h * (MAX_W/w);
				w = MAX_W;
				imgInstance.set({scaleX: w/imgInstance.width, scaleY: h/imgInstance.height});
			}
			if (h > MAX_H) {
				w = w * (MAX_H/h);
				h = MAX_H;
			}
			canvas.setBackgroundImage(imgInstance, function() {
				canvas.setDimensions({width: w, height: h});
				canvas.renderAll();
			}, {
				originX: 'left',
				originY: 'top',
			});

			console.log('OK');
		};

		img.src = reader.result;
	};

	if (files[0]) {
		reader.readAsDataURL(files[0])
	}
}

function addLocalImage(files) {

	console.log('Loading "' + files[0].name + '"...');

	var reader = new FileReader();
	reader.onload = function() {
		var img = new Image();

		img.onload = function () {
			/*
			var canvas = document.getElementById("canvas");
			canvas.width = img.width;
			canvas.height = img.height;
			var ctx = canvas.getContext("2d");
			ctx.drawImage(this, 0, 0);
			*/
			var imgInstance = new fabric.Image(img);
			canvas.add(imgInstance);

			console.log('OK');
		};

		img.src = reader.result;
	};

	if (files[0]) {
		reader.readAsDataURL(files[0])
	}
}

function addRemoteBackground(URL) {
	var img = new Image();
	img.crossOrigin = 'Anonymous';

	console.log('Loading image from', URL);

	img.onload = function () {
		var imgInstance = new fabric.Image(img);
		var w = imgInstance.width;
		var h = imgInstance.height;
		if (w > MAX_W) {
			h = h * (MAX_W/w);
			w = MAX_W;
			imgInstance.set({scaleX: w/imgInstance.width, scaleY: h/imgInstance.height});
		}
		if (h > MAX_H) {
			w = w * (MAX_H/h);
			h = MAX_H;
		}
		canvas.setBackgroundImage(imgInstance, function() {
			canvas.setDimensions({width: w, height: h});
			canvas.renderAll();
		}, {
			originX: 'left',
			originY: 'top',
		});

		console.log('OK');
	};

	img.src = URL;
}

function addRemoteImage(URL) {
	var img = new Image();
	img.crossOrigin = 'Anonymous';

	console.log('Loading image from', URL);

	img.onload = function () {
		var imgInstance = new fabric.Image(img);
		canvas.add(imgInstance);

		console.log('OK');
	};

	img.src = URL;
}

function addText() {

	console.log('Adding text object');

	var itext = new fabric.IText('Escribí algo acá', {
		left: 20,
		top: 20,
		padding: 2,
		cursorDelay: 400,
		cursorDuration: 200,
		fontSize: 40,
		fontWeight: 'bold',
		fontFamily: 'Sans',
	});
	itext.selectAll();
	canvas.add(itext).setActiveObject(itext);
	itext.enterEditing();
}

function setTextColor(c) {
	var objects = canvas.getActiveObjects();
	objects.forEach(function(e) {
		e.set({fill: c});
	});
	canvas.renderAll();
}

function setFont() {
	var objects = canvas.getActiveObjects();
	objects.forEach(function(e) {
		e.set({fontFamily: document.getElementById("font").value});
	});
	canvas.renderAll();
}

function removeSelected() {
	var objects = canvas.getActiveObjects();
	objects.forEach(function(e) {
		canvas.discardActiveObject(e);
		canvas.remove(e);
	});
	canvas.renderAll();
}

$(function() {
	canvas = new fabric.Canvas('canvas');

	var bringToFront = function(e) {
		canvas.bringToFront(e.target);
		canvas.renderAll();
	};
	canvas.on('selection:created', bringToFront);
	canvas.on('selection:updated', bringToFront);

	// Initial states
	$("#CMA_image_selector > h2").text("Imagen inicial");
	$('#toolbox').hide();
	$('#submit').hide();
	$('#CMA_image_selector_close').hide();
	$('#CMA').show(); // this should be at the end

	var initial_state_flag = true;

	var hideLoader = function(bgimgFunc, objimgFunc) {
		return function(arg) {
			if (initial_state_flag) {
				initial_state_flag = false;
				bgimgFunc(arg);
				$('#CMA_image_selector').hide();
				$("#CMA_image_selector > h2").text("Imagen para collage");
				$('#CMA_image_selector_close').show();
				$('#toolbox').show();
				$('#submit').show();
			} else {
				objimgFunc(arg);
				$('#CMA_image_selector').hide();
			}
		};
	}

	$('#CMA_file_button').click(function() {
		var input = document.createElement('input');
		input.type = 'file';
		input.accept = 'image/*';

		input.addEventListener('change', function(e) {
			(hideLoader(addLocalBackground, addLocalImage))(e.target.files);
		});

		input.click();
	});

	$('#CMA_URL_button').click(function() {
		(hideLoader(addRemoteBackground, addRemoteImage))(document.getElementById('URL').value);
		document.getElementById('URL').value = '';
	});

	$('#CMA_image_selector_close').click(function() {
		$('#CMA_image_selector').fadeOut(100);
	});

	// Toolbar
	$('#CMA_text_button').click(function() {
		$('#CMA_text_more').toggleClass('open_toolbox', true);
		addText();
	});

	$('#CMA_image_button').click(function() {
		$('#CMA_text_more').toggleClass('open_toolbox', false);
		$('#CMA_image_selector').fadeIn(100);
	});

	$('#submit').click(function() {
		var statusLabel = document.getElementById("status");
		statusLabel.innerHTML = "uploading...";

		var formData = new FormData();
		formData.set("title", document.getElementById("title").value);
		//formData.set("file", document.getElementById("file").files[0]);
		var byteString = atob(canvas.toDataURL().split(',')[1]);
		var ia = new Uint8Array(byteString.length);
		for (var i = 0; i < byteString.length; i++) {
			ia[i] = byteString.charCodeAt(i);
		}
		formData.set("file", (new File( [(new Blob([ia], {type:'image/png'}))], 'blob.png', {type:'image/png'})));
		formData.set("description", document.getElementById("description").value);

		var xhr = new XMLHttpRequest();
		xhr.open("POST", "/m/", true);
		xhr.onreadystatechange = function () {
			if (xhr.readyState === 4) {
				var re = JSON.parse(xhr.response);

				if (re.errors) {
					statusLabel = document.getElementById("status");
					statusLabel.innerHTML = '<label class="error">' + re.errors[0].detail + '</label>';
				}

				if (re.memeId) {
					window.location.replace("/m/" + re.memeId);
				}
			}
		};
		xhr.send(formData);
	});
});

