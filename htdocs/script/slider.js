var vImageSection = document.querySelectorAll('#imagini section');
var lg = vImageSection.length;

var vBtn = document.querySelectorAll('#arrayBar ul li span');

var current = 0;
function playLoop(){
	
	++current;
	if(current == lg){
		
		vImageSection[lg-1].className = "sliderHiddenSection";
		vImageSection[0].className = "sliderVisibleSection";
		
		vBtn[lg-1].className = "";
		vBtn[0].className = "arraySelected";
		
		current = 0;
		
	} else {
		
		vImageSection[current-1].className = "sliderHiddenSection";
		vImageSection[current].className = "";
		
		vBtn[current-1].className = "";
		vBtn[current].className = "arraySelected";
		
	}
	
}

setInterval(function(){
	playLoop();
	
}, 3000);