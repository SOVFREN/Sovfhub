function fixed_layout_draggable() {
    const draggableDivs = document.querySelectorAll(".fixed_draggable_layout");
    let offsetX, offsetY, isDragging = false, selectedDiv = null;

    function fixed_draggable_onStart(event) {

        var target = event.target;

        if (target instanceof SVGElement) {
            var className = target.getAttribute("class");
        } else {
            var className = target.className;
        }

        if (className === 'close_window_icon') {
            return;
        }

        selectedDiv = event.target.closest('.fixed_draggable_layout');
        if (selectedDiv) {
            const touch = event.touches ? event.touches[0]: event;
            offsetX = touch.clientX - selectedDiv.getBoundingClientRect().left;
            offsetY = touch.clientY - selectedDiv.getBoundingClientRect().top;
            isDragging = true;
            selectedDiv.style.cursor = "grabbing";
            event.preventDefault();
        }
    }

    function fixed_draggable_onMove(event) {
        if (isDragging && selectedDiv) {
            const touch = event.touches ? event.touches[0]: event;
            const newX = touch.clientX - offsetX;
            const newY = touch.clientY - offsetY;
            const maxX = window.innerWidth - selectedDiv.clientWidth;
            const maxY = window.innerHeight - selectedDiv.clientHeight;
            selectedDiv.style.left = Math.min(maxX, Math.max(0, newX)) + "px";
            selectedDiv.style.top = Math.min(maxY, Math.max(0, newY)) + "px";
        }
    }

    function fixed_draggable_onEnd() {
        if (selectedDiv) {
            isDragging = false;
            selectedDiv.style.cursor = "grab";
            selectedDiv = null;
        }
    }

    draggableDivs.forEach((div) => {
        div.addEventListener("mousedown", fixed_draggable_onStart);
        div.addEventListener("touchstart", fixed_draggable_onStart);
        window.addEventListener("mousemove", fixed_draggable_onMove);
        window.addEventListener("touchmove", fixed_draggable_onMove);
        window.addEventListener("mouseup", fixed_draggable_onEnd);
        window.addEventListener("touchend", fixed_draggable_onEnd);
    });
}