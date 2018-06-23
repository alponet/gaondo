var MAX_W = 636;
var MAX_H = 1000;
var canvas;

function addLocalBackground(files) {

	console.log('Loading "' + files[0].name + '"...');

	if (files[0]) {
		loadImage(files[0], function(img) {
			var imgInstance = new fabric.Image(img);
			var w = imgInstance.width;
			var h = imgInstance.height;
			var c_w = $('#canvas_area').width(); 
			var c_h = $('#canvas_area').height(); 
			//canvas.setZoom(c_w / MAX_W);
			//canvas.calcOffset();
			console.log(w,h);
			if (w > c_w) {
				h = h * (c_w/w);
				w = c_w;
				imgInstance.set({scaleX: w/imgInstance.width, scaleY: h/imgInstance.height});
			}
			/*
			if (h > c_h) {
				w = w * (c_h/h);
				h = c_h;
			}
			*/
			console.log(w,h);
			canvas.setBackgroundImage(imgInstance, function() {
				canvas.setDimensions({width: w, height: h}, {cssOnly: false});
				canvas.renderAll();
			}, {
				originX: 'left',
				originY: 'top',
			});

			console.log('OK');
		}, {
			orientation: true,
			meta: true
		});
	}
}

function addLocalImage(files) {

	console.log('Loading "' + files[0].name + '"...');

	var reader = new FileReader();
	reader.onload = function() {
		var img = new Image();

		img.onload = function() {
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

	img.onload = function() {
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

	var error_flag = false;
	img.onerror = function(e) {
		console.log('ERROR:', e);
		if (!error_flag) {
			error_flag = true;
			img.src = '//gaondo.com/get-ok?q=' + encodeURIComponent(URL);
		} else {
			console.log('Inaccessible content');
		}
	};

	img.src = URL;
}

function addRemoteImage(URL) {
	var img = new Image();
	img.crossOrigin = 'Anonymous';

	console.log('Loading image from', URL);

	img.onload = function() {
		var imgInstance = new fabric.Image(img);
		canvas.add(imgInstance);

		console.log('OK');
	};

	var error_flag = false;
	img.onerror = function(e) {
		console.log('ERROR:', e);
		if (!error_flag) {
			error_flag = true;
			img.src = '//gaondo.com/get-ok?q=' + encodeURIComponent(URL);
		} else {
			console.log('Inaccessible content');
		}
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
		strokeWidth: 2,
	});
	canvas.setActiveObject(itext);
	setFont(); // Use default font
	setTextColor("white");
	itext.selectAll();
	canvas.add(itext).setActiveObject(itext);
	itext.enterEditing();
}

function setTextColor(c) {
	var objects = canvas.getActiveObjects();
	objects.forEach(function(e) {
		switch (c) {
			case "white":
				e.set({fill: "white", stroke: "black"});
				break;
			case "black":
				e.set({fill: "black", stroke: "white"});
				break;
			default:
				console.log("Color selection error");
		}
	});
	canvas.renderAll();
}

function loadAndUse(font) {
	var myfont = new FontFaceObserver(font);
	myfont.load().then(function() {
			canvas.getActiveObject().set('fontFamily', font);
			canvas.getActiveObject().set('fontWeight', 'bold');
			canvas.requestRenderAll();
	}).catch(function(e) {
		console.log(e)
		alert('font loading failed ' + font);
	});
}

function setFont() {
	var objects = canvas.getActiveObjects();
	objects.forEach(function(e) {
		var font = document.getElementById("font").value;
		if (font == "impactreg") {
			loadAndUse(font);
		}
		e.set({fontFamily: font});
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
		input.id = 'CMA_file_input';
		//input.accept = 'image/*'; // XXX Chrome for Android is currently crashing with this one (2018-04-30)
		input.value = null;
		input.hidden = true;

		document.body.appendChild(input); // Workaround for WebKit on iOS (change event didn't trigger)

		input.addEventListener('change', function(e) {
			(hideLoader(addLocalBackground, addLocalImage))(e.target.files);
			document.body.removeChild(input); // XXX if user cancels this is never removed
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
		statusLabel.innerHTML = "subiendo...";

        $("#canvas").get(0).toBlob(function(blob) {
            var formData = new FormData();

            formData.append("title", document.getElementById("title").value);
            formData.append("file", blob, "file.jpeg");
            formData.append("description", document.getElementById("description").value);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/m/", true);
            xhr.onreadystatechange = function() {
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
        }, "image/jpeg", 1.0);
	});
});

