(function () {

    "(use strict)";
    const header = document.querySelector(".header");
    window.addEventListener("scroll", () => {
        if (header !== null && window.scrollY > 0) {
            header.classList.add("sticky");
        }
        else {
            header.classList.remove("sticky");
        }
    })

    const mobileMenuBtn = document.querySelector(".mobile-menu-btn")
    if (mobileMenuBtn) {
        const overlayDiv = document.createElement("div")
        overlayDiv.setAttribute("class", "overlay");
        overlayDiv.style.cssText = `position:fixed;width:100%;height:100%;insets:0;background-color:#000;opacity:0.4;z-index:95`
        const body = document.querySelector("body")
        const sidebar = document.querySelector(".aside");
        mobileMenuBtn.addEventListener("click", () => {
            sidebar.style.cssText = sidebar.style.cssText == "" ? `transform: translateX(0);` : "";
            var isOverlay = document.querySelector(".overlay");
            if (isOverlay) {
                isOverlay.remove()
            } else {
                body.append(overlayDiv)
            }
        })

        if (overlayDiv) {
            overlayDiv.addEventListener("click", () => {
                overlayDiv.remove()
                sidebar.style.cssText = sidebar.style.cssText == "" ? `transform: translateX(0);` : "";
            })
        }

        const navLinks = sidebar.querySelectorAll(".nav-link")
        navLinks.forEach((link) => {
            link.addEventListener("click", () => {
                overlayDiv.remove()
                sidebar.style.cssText = sidebar.style.cssText == "" ? `transform: translateX(0);` : "";
            })
        })
    }
}())