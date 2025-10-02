function toggleDropdown() {
    const dropdown = document.getElementById('userDropdown');
    const button = document.querySelector('.user-btn');

    dropdown.classList.toggle('show');
    button.classList.toggle('active');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userDropdown');
    const button = document.querySelector('.user-btn');

    if (!button.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.remove('show');
        button.classList.remove('active');
    }
});
