var field = [];
var canvas;
var ctx;
$(document).ready(function() {
	canvas = $("#canvas")[0];
	ctx = canvas.getContext("2d");
	resetCanvas();
	
	$("#canvas").click(function(e) {
		var offset = $(this).offset();
		var point = {x:Math.floor((e.pageX-offset.left)/11),y:Math.floor((e.pageY-offset.top)/11)};
		if (shouldAdd(point)) {
			field.push(point);
			drawPoint(point);
		}else {
			field = killPoint(point);
			clearPoint(point);
		}
	});
	
	$("#next").click(function() {
		$.post("php/core.php", {points:field}).done(function(data) {
			field = JSON.parse(data);
			if (!field) {
				field = [];
			}
			redrawCanvas();
		});
	});
	
	$("#reset").click(function() {
		field = [];
		redrawCanvas();
	});
});

function killPoint(point) {
	var newPoints = [];
	for (var i = 0; i < field.length; ++i) {
		if (!(field[i].x == point.x && field[i].y == point.y)) {
			newPoints.push(field[i]);
		}	
	}
	return newPoints;
}

function addPoint(realX, realY) {
	var point = {x:Math.floor(realX/11),y:Math.floor(realY/11)};
	if (!shouldAdd(point)) {
		return null;
	}
	field.push(point);
	return point;
}

function shouldAdd(point) {
	for (var i = 0; i < field.length; ++i) {
		if (field[i].x == point.x && field[i].y == point.y) {
			return false;
		}
	}
	return true;
}

function redrawCanvas() {
	resetCanvas();
	for (var i = 0; i < field.length; ++i) {
		drawPoint(field[i]);
	}
}

function resetCanvas() {
	ctx.fillStyle = '#bdbdbd';
	ctx.fillRect(0,0,canvas.width,canvas.height);
}

function drawPoint(point) {
	var left = point.x * 11;
	var top = point.y * 11;
	ctx.fillStyle = '#ffffff';
	ctx.fillRect(left + 1, top + 1, 10, 10);
}

function clearPoint(point) {
	var left = point.x * 11;
	var top = point.y * 11;
	ctx.fillStyle = '#bdbdbd';
	ctx.fillRect(left + 1, top + 1, 10, 10);
}