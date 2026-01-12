document.addEventListener('DOMContentLoaded', function() {
    // Animated Grid Background
    const canvas = document.getElementById('gridCanvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        
        let width = canvas.width = window.innerWidth;
        let height = canvas.height = window.innerHeight;
        
        const particles = [];
        const isMobile = window.innerWidth < 768;
        const particleCount = isMobile ? 40 : 80;
        const connectionDistance = isMobile ? 100 : 150;
        const mouse = { x: null, y: null, radius: isMobile ? 100 : 150 };
        
        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.vx = (Math.random() - 0.5) * 0.5;
                this.vy = (Math.random() - 0.5) * 0.5;
                this.size = Math.random() * 2 + 1;
            }
            
            update() {
                this.x += this.vx;
                this.y += this.vy;
                
                if (this.x < 0 || this.x > width) this.vx *= -1;
                if (this.y < 0 || this.y > height) this.vy *= -1;
                
                if (mouse.x && mouse.y) {
                    const dx = mouse.x - this.x;
                    const dy = mouse.y - this.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance < mouse.radius) {
                        const force = (mouse.radius - distance) / mouse.radius;
                        this.x -= dx * force * 0.03;
                        this.y -= dy * force * 0.03;
                    }
                }
            }
            
            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = '#6366f1';
                ctx.fill();
            }
        }
        
        function init() {
            particles.length = 0;
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
        }
        
        function connectParticles() {
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance < connectionDistance) {
                        const opacity = 1 - (distance / connectionDistance);
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(99, 102, 241, ${opacity * 0.2})`;
                        ctx.lineWidth = 1;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }
        }
        
        function animate() {
            ctx.clearRect(0, 0, width, height);
            
            particles.forEach(particle => {
                particle.update();
                particle.draw();
            });
            
            connectParticles();
            requestAnimationFrame(animate);
        }
        
        window.addEventListener('resize', () => {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
            init();
        });
        
        window.addEventListener('mousemove', (e) => {
            mouse.x = e.x;
            mouse.y = e.y;
        });
        
        window.addEventListener('mouseout', () => {
            mouse.x = null;
            mouse.y = null;
        });

        window.addEventListener('touchmove', (e) => {
            if (e.touches.length > 0) {
                mouse.x = e.touches[0].clientX;
                mouse.y = e.touches[0].clientY;
            }
        });

        window.addEventListener('touchend', () => {
            mouse.x = null;
            mouse.y = null;
        });
        
        init();
        animate();
    }
    
    // Mobile menu functionality
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    const body = document.body;
    
    function openMobileMenu() {
        if (mobileMenu) {
            mobileMenu.classList.add('active');
        }
        if (mobileMenuOverlay) {
            mobileMenuOverlay.classList.add('active');
        }
        if (mobileMenuBtn) {
            mobileMenuBtn.textContent = '✕';
            mobileMenuBtn.setAttribute('aria-label', 'Close mobile menu');
        }
        body.style.overflow = 'hidden';
    }
    
    function closeMobileMenu() {
        if (mobileMenu) {
            mobileMenu.classList.remove('active');
        }
        if (mobileMenuOverlay) {
            mobileMenuOverlay.classList.remove('active');
        }
        if (mobileMenuBtn) {
            mobileMenuBtn.textContent = '☰';
            mobileMenuBtn.setAttribute('aria-label', 'Toggle mobile menu');
        }
        body.style.overflow = '';
    }
    
    function toggleMobileMenu() {
        if (mobileMenu && mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    }
    
    if (mobileMenuBtn && mobileMenu) {
        // Toggle menu on button click
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleMobileMenu();
        });

        // Close menu when clicking overlay
        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', function(e) {
                e.stopPropagation();
                closeMobileMenu();
            });
        }

        // Close menu when clicking on menu links
        const menuLinks = mobileMenu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Allow the link to work, then close menu after a short delay
                setTimeout(() => {
                    closeMobileMenu();
                }, 300);
            });
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('active')) {
                closeMobileMenu();
            }
        });

        // Close menu on window resize if window becomes larger
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 768 && mobileMenu && mobileMenu.classList.contains('active')) {
                    closeMobileMenu();
                }
            }, 250);
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#' || href === '') {
                return;
            }
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                if (mobileMenu && mobileMenu.classList.contains('active')) {
                    setTimeout(() => {
                        closeMobileMenu();
                    }, 300);
                }
            }
        });
    });
});
