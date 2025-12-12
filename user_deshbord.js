document.addEventListener('DOMContentLoaded', () => {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();

            // Remove active class from all links
            tabLinks.forEach(l => l.classList.remove('active'));

            // Add active class to the clicked link
            e.target.classList.add('active');

            // Hide all content sections
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            // Show the corresponding content section
            const tabId = e.target.dataset.tab;
            const targetSection = document.getElementById(tabId);
            if (targetSection) {
                targetSection.classList.add('active');
            }
        });
    });
});