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
    var centerX = (screenWidth / 2) - 130;
    var peeringY = 75;

    /* CSS transition ~600ms — or stepped: wolf.slideTo(centerX, peeringY, 5, 30); */
    wolf.slideTo(centerX, peeringY, 600);
}

/* login.php sets window.lupoLoginSigningText before this file loads */
document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('.login-form');
    if (!form) {
        return;
    }
    form.addEventListener('submit', function () {
        var submitButton = document.querySelector('.login-btn');
        if (!submitButton) {
            return;
        }
        var pending = (typeof window.lupoLoginSigningText === 'string') ? window.lupoLoginSigningText : '…';
        submitButton.textContent = pending;
        submitButton.disabled = true;
    });
});
