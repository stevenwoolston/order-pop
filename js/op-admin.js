window.addEventListener("load", function () {

    var tabs = document.querySelectorAll(".op-nav-tab-wrapper a");

    for (var i = 0; i < tabs.length; i++) {
        tabs[i].addEventListener("click", switchTab);
    }

    function switchTab(event) {
        event.preventDefault();

        document.querySelector(".nav-tab-active").classList.remove("nav-tab-active");
        document.querySelector(".tab-pane.active").classList.remove("active");

        var clickedTab = event.currentTarget;
        var anchor = event.target;
        var activePaneId = anchor.getAttribute("href");

        clickedTab.classList.add("nav-tab-active");
        document.querySelector(activePaneId).classList.add("active");
    }

});