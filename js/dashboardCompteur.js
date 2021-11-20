let counterDisplayElem = document.querySelector('#compteurValeur');
let counterPlusElem = document.querySelector('.compteurAdd');
let counterMinusElem = document.querySelector('.compteurMin');
let count = 0;
updateDisplay();


counterPlusElem.addEventListener("click",()=>{
	count++;
	updateDisplay();
});

counterMinusElem.addEventListener("click",()=>{
    if (count>0) {
    	count--;
    	updateDisplay();
    }

});



function updateDisplay(){
    counterDisplayElem.value = count;
	document.cookie = "nbBoisson="+count;
	
};
