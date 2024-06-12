import "./bootstrap";

let attrsKill = ["snapshot"];

function snapKill() {
    document
        .querySelectorAll(["header", "nav", "div"])
        .forEach(function (element) {
            for (let i in attrsKill) {
                if (element.getAttribute(`wire:${attrsKill[i]}`) !== null) {
                    element.removeAttribute(`wire:${attrsKill[i]}`);
                }
            }
        });
}

window.addEventListener("load", (ev) => {
    snapKill();
});
