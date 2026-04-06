function toggleSavedCollectionsDropdown(button) {
    const dropdown = button.closest('.saved-collections-dropdown');
    const isActive = dropdown.classList.contains('active');
    
    // Close all other dropdowns and their submenus
    document.querySelectorAll('.saved-collections-dropdown').forEach(d => {
        if (d !== dropdown) {
            d.classList.remove('active');
            d.querySelectorAll('.saved-collections-submenu').forEach(s => {
                s.classList.remove('active');
                const content = s.querySelector('.saved-collections-submenu-content');
                if (content && content.parentNode === document.body) {
                    content.remove();
                } else if (content) {
                    content.style.display = 'none';
                }
                content.classList.remove('active');
            });
        }
    });
    
    // Toggle this dropdown
    const newState = !isActive;
    dropdown.classList.toggle('active', newState);
    button.setAttribute('aria-expanded', newState.toString());
}

let openSubmenuContent = null;

function toggleSubmenu(trigger, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    const submenu = trigger.closest('.saved-collections-submenu');
    if (!submenu) return;
    
    const isActive = submenu.classList.contains('active');
    const submenuContent = submenu.querySelector('.saved-collections-submenu-content');
    if (!submenuContent) return;
    
    const isOpening = !isActive;
    const container = submenu.parentElement;
    
    let mouseX = null;
    let mouseY = null;
    if (event && event.clientX && event.clientY) {
        mouseX = event.clientX;
        mouseY = event.clientY;
    }
    
    if (container) {
        container.querySelectorAll(':scope > .saved-collections-submenu').forEach(s => {
            if (s !== submenu) {
                s.classList.remove('active');
                const content = s.querySelector('.saved-collections-submenu-content');
                if (content && content.classList.contains('active') && content.parentNode === document.body) {
                    content.remove();
                } else if (content) {
                    content.style.display = 'none';
                }
                if (content) {
                    content.classList.remove('active');
                }
                s.querySelectorAll('.saved-collections-submenu').forEach(nested => {
                    nested.classList.remove('active');
                    const nestedContent = nested.querySelector('.saved-collections-submenu-content');
                    if (nestedContent && nestedContent.parentNode === document.body) {
                        nestedContent.remove();
                    } else if (nestedContent) {
                        nestedContent.style.display = 'none';
                    }
                    if (nestedContent) {
                        nestedContent.classList.remove('active');
                    }
                });
            }
        });
    }
    
    if (openSubmenuContent && openSubmenuContent.parentNode === document.body && 
        !openSubmenuContent.contains(submenu) && openSubmenuContent.id !== submenuContent.id) {
        openSubmenuContent.remove();
    }
    
    if (isOpening) {
        submenu.classList.add('active');
        const triggerRect = trigger.getBoundingClientRect();
        let positionedSubmenu = submenuContent;
        
        if (submenuContent.parentNode !== document.body || !submenuContent.classList.contains('active')) {
            positionedSubmenu = submenuContent.cloneNode(true);
            if (submenuContent.id) {
                positionedSubmenu.setAttribute('data-source-id', submenuContent.id);
            }
            positionedSubmenu.querySelectorAll('.saved-collections-submenu-content').forEach(c => {
                c.style.display = 'none';
                c.classList.remove('active');
            });
            document.body.appendChild(positionedSubmenu);
            openSubmenuContent = positionedSubmenu;
        } else {
            positionedSubmenu = openSubmenuContent;
        }
        
        positionedSubmenu.classList.add('active');
        positionedSubmenu.style.position = 'absolute';
        positionedSubmenu.style.display = 'block';
        
        let leftPos = triggerRect.right + 4;
        let topPos = triggerRect.top;
        
        if (!triggerRect || triggerRect.width === 0 || triggerRect.height === 0 || 
            isNaN(leftPos) || leftPos <= 0 || isNaN(topPos) || topPos <= 0) {
            const parentRect = trigger.parentElement?.getBoundingClientRect();
            if (parentRect && parentRect.width > 0 && parentRect.height > 0) {
                leftPos = parentRect.right + 4;
                topPos = parentRect.top;
            } else if (mouseX !== null && mouseY !== null) {
                leftPos = mouseX + 4;
                topPos = mouseY;
            } else {
                leftPos = 200;
                topPos = 200;
            }
        }
        
        const viewportWidth = window.innerWidth;
        const submenuWidth = positionedSubmenu.offsetWidth || 280;
        if (leftPos + submenuWidth > viewportWidth) {
            leftPos = triggerRect.left - submenuWidth - 4;
            if (leftPos < 0) {
                leftPos = Math.max(4, (viewportWidth - submenuWidth) / 2);
            }
        }
        
        const viewportHeight = window.innerHeight;
        const submenuHeight = positionedSubmenu.offsetHeight;
        if (topPos + submenuHeight > viewportHeight) {
            topPos = Math.max(4, viewportHeight - submenuHeight - 10);
        }
        
        positionedSubmenu.style.left = Math.max(0, leftPos) + 'px';
        positionedSubmenu.style.top = Math.max(0, topPos) + 'px';
        positionedSubmenu.style.zIndex = '10001';
    } else {
        submenu.classList.remove('active');
        let positionedSubmenu = null;
        if (submenuContent.id) {
            positionedSubmenu = document.body.querySelector(`#${submenuContent.id}.active`) || 
                               document.body.querySelector(`[data-source-id="${submenuContent.id}"].active`);
        }
        if (positionedSubmenu) {
            positionedSubmenu.remove();
        }
        submenuContent.classList.remove('active');
        submenuContent.style.display = 'none';
        openSubmenuContent = null;
    }
}

let resizeTimeout;
function handleResizeOrScroll() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        const activeSubmenu = document.querySelector('.saved-collections-submenu.active');
        if (activeSubmenu) {
            const trigger = activeSubmenu.querySelector('.saved-collections-submenu-trigger');
            if (trigger) {
                toggleSubmenu(trigger, null);
                toggleSubmenu(trigger, null);
            }
        }
    }, 100);
}

window.addEventListener('scroll', handleResizeOrScroll, true);
window.addEventListener('resize', handleResizeOrScroll);

document.addEventListener('click', function(event) {
    if (!event.target.closest('.saved-collections-dropdown') && 
        !event.target.closest('.saved-collections-submenu-content')) {
        document.querySelectorAll('.saved-collections-dropdown').forEach(d => {
            d.classList.remove('active');
        });
        document.querySelectorAll('.saved-collections-submenu').forEach(s => {
            s.classList.remove('active');
        });
        document.querySelectorAll('.saved-collections-submenu-content').forEach(content => {
            if (content.parentNode === document.body) {
                content.remove();
            }
            content.classList.remove('active');
            content.style.display = 'none';
        });
        openSubmenuContent = null;
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.saved-collections-dropdown').forEach(d => {
            d.classList.remove('active');
        });
        document.querySelectorAll('.saved-collections-submenu').forEach(s => {
            s.classList.remove('active');
        });
        document.querySelectorAll('.saved-collections-submenu-content').forEach(content => {
            if (content.parentNode === document.body) {
                content.remove();
            }
            content.classList.remove('active');
            content.style.display = 'none';
        });
        openSubmenuContent = null;
    }
});

function toggleMenu(menuId) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    for (var i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.id !== menuId) {
            openDropdown.classList.remove('show');
        }
    }
    document.getElementById(menuId).classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.matches('img')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            dropdowns[i].classList.remove('show');
        }
    }
}

function addNewItem(type, parentName) {
    if (typeof parentName === 'undefined') {
        parentName = '';
    }
    if (typeof event !== 'undefined' && event) {
        event.stopPropagation();
    }
    var S = (window.LUPO_HDR && window.LUPO_HDR.strings) ? window.LUPO_HDR.strings : {};
    var message = (type === 'main')
        ? (S.prompt_main_tab || 'Enter name for new Main Tab:')
        : (S.prompt_sub_tab || 'Enter new Sub-Tab name for "%s":').replace('%s', parentName);
    var userInput = prompt(message);
    if (userInput !== null && userInput.trim() !== '') {
        var okTmpl = S.add_success || 'Successfully added "%s" to your collection!';
        alert(okTmpl.replace('%s', userInput));
    }
}
