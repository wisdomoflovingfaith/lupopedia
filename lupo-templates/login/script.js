/**
 * Login template animation — uses lupo-layers.js (DynLayer-compatible, no eval).
 *
 * Alternative (legacy-style globals): call LupoLayerInit() or DynLayerInit(), then use
 *   window.header (from id="headerDiv"), window.login (from id="loginDiv")
 *   e.g. header.slideTo(x, y, 5, 30); — 4-arg form = stepped slide like old DynLayer.
 */
function initApp() {
    if (typeof LupoLayer === 'undefined') {
        return;
    }

    /* Explicit layers (avoids shadowing window.login in some environments). */
    var wolf = new LupoLayer('headerDiv');
    var loginBox = new LupoLayer('loginDiv');
    if (!wolf.elm) {
        return;
    }

    wolf.onSlideEnd = function () {
        wolf.setZ(10);
        if (loginBox.elm) {
            loginBox.setZ(5);
        }
    };

    var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    var centerX = (screenWidth / 2) - 100;
    var peeringY = 150;

    /* CSS transition ~600ms — or stepped: wolf.slideTo(centerX, peeringY, 5, 30); */
    wolf.slideTo(centerX, peeringY, 600);
}
