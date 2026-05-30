(function() {
    function AdminInterface() {
        this.navLinks = document.querySelectorAll('[data-admin-target]');
        this.iframe = document.getElementById('channels-admin-frame');
        this.init();
    }

    AdminInterface.prototype.init = function() {
        var self = this;
        if (!this.iframe) {
            return;
        }
        for (var i = 0; i < this.navLinks.length; i++) {
            this.navLinks[i].addEventListener('click', function(event) {
                event.preventDefault();
                self.setActive(this);
                self.iframe.src = this.getAttribute('href');
            });
        }
    };

    AdminInterface.prototype.setActive = function(activeLink) {
        for (var i = 0; i < this.navLinks.length; i++) {
            this.navLinks[i].classList.remove('active');
        }
        activeLink.classList.add('active');
    };

    window.AdminInterface = AdminInterface;
})();
