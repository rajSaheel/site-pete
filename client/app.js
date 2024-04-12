import crawlAPI from "./services/crawl.js";

const menu = document.querySelector('#mobile-menu');
const menuLinks = document.querySelector('.navbar__menu');
const navLogo = document.querySelector('#navbar__logo');
const integrationClass = document.querySelector('.integration');
const loading = document.querySelector('.loading-wrapper');
const ratingLabel = document.querySelectorAll(".rating-label")
const starsFigureCollect = document.querySelector("#star-collection-id")
const analyzeBtn = document.querySelector(".analyze-btn")
const urlInput = document.querySelector(".searchbox-wrap input")
const successAudio = document.getElementById("success")
const failureAudio = document.getElementById("failure")
let ready = true

const starArr = []
for (let index = 1; index < starsFigureCollect.childNodes.length; index += 2) {
	starArr.push(starsFigureCollect.childNodes[index])
}

// Display Mobile Menu
const mobileMenu = () => {
	menu.classList.toggle('is-active');
	menuLinks.classList.toggle('active');
}

menu.addEventListener('click', mobileMenu);

//Show active menu while scrolling
const highlightMenu = () => {
	const elem = document.querySelector('.highlight');
	const homeMenu = document.querySelector('#home-page');
	const aboutMenu = document.querySelector('#about-page');
	const servicesMenu = document.querySelector('#services-page');
	let scrollPos = window.scrollY;

	//adds 'highlight' class to my menu items
	if (window.innerWidth > 960 && scrollPos < 700) {
		homeMenu.classList.add('highlight');
		aboutMenu.classList.remove('highlight');
		return
	} else if (window.innerWidth > 960 && scrollPos < 1400) {
		aboutMenu.classList.add('highlight');
		homeMenu.classList.remove('highlight');
		servicesMenu.classList.remove('highlight');
		return
	} else if (window.innerWidth > 960 && scrollPos < 2345) {
		servicesMenu.classList.add('highlight');
		aboutMenu.classList.remove('highlight');
		return
	}

	if ((elem && window.innerWidth < 960 && scrollPos < 600) || elem) {
		elem.classList.remove('highlight');
	}
}

window.addEventListener('scroll', highlightMenu);
window.addEventListener('click', highlightMenu);

// Close mobile menu when clicking on a menu item
const hideMobileMenu = () => {
	const menuBars = document.querySelector('.is-active');
	if (window.innerWidth <= 913 && menuBars) {
		menu.classList.toggle('is-active');
		menuLinks.classList.remove('active');
	}
}

const displayRating = (points) => {
	loading.style.display = "none"
	if (points !== undefined && points !== null) {
		const pointsRound = Math.round(points)
		ratingLabel[0].textContent = "Peté gives"
		ratingLabel[1].textContent = "to this link"
		let i = 0
		for (i; i < 5; i++) {
			if (i < pointsRound) {
				starArr[i].style.display = "flex"
				starArr[i].style.color = `#FFD700`
			} else {
				starArr[i].style.display = "flex"
			}
		}
		successAudio.play()
		return
	} else if (points === null) {
		ratingLabel[0].textContent = "No website found!"
		ratingLabel[1].textContent = ""
		for (let i = 0; i < 5; i++) {
			starArr[i].style.display = "none"
		}
		failureAudio.play()
		return
	} else if (points === undefined) {
		ratingLabel[0].textContent = "Something went wrong!"
		ratingLabel[1].textContent = ""
		for (let i = 0; i < 5; i++) {
			starArr[i].style.display = "none"
		}
		failureAudio.play()
		return
	}
}

const clearStars = () => {
	ratingLabel[0].textContent = "Loading...please wait"
	ratingLabel[1].textContent = ""
	for (let i = 0; i < 5; i++) {
		starArr[i].style.color = " rgb(206, 213, 219)"
		starArr[i].style.display = "none"
	}
	loading.style.display = "flex"
}

const analyze = async (url) => {
	// console.log(url)
	if (
		!(url.match(
			/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g
		))) {
			alert("Please enter a valid URL")
			return
		}
	clearStars()
	ready = false
	integrationClass.style.visibility = "visible"
		try {
			// const response = { total: 4.5 }
			const response = await crawlAPI(url);
			loading.style.display = "none"
			if (response) {
				console.log(response)
				displayRating(response.total)
			} else displayRating(undefined)
		} catch (e) {
			console.log(e)
			displayRating(undefined)
		}
		ready = true

}



analyzeBtn.addEventListener('click', () => analyze(urlInput.value))
menuLinks.addEventListener('click', hideMobileMenu);
navLogo.addEventListener('click', hideMobileMenu);

urlInput.addEventListener("keypress", (event)=> {
	// If the user presses the "Enter" key on the keyboard
	if (event.key === "Enter") {
	  // Cancel the default action, if needed
	  event.preventDefault();
	  // Trigger the button element with a click
	  analyze(urlInput.value)
	}
})