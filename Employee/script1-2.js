const sidebar = document.getElementById('sidebar');
const logo = document.querySelector('.sidebar-logo');

// Function to close all submenus
function closeAllSubmenus() {
  const submenus = document.querySelectorAll('.submenu');
  const menuItems = document.querySelectorAll('.sidebar-item-with-submenu');
  submenus.forEach(submenu => submenu.classList.remove('open'));
  menuItems.forEach(item => item.classList.remove('open'));
}

if (logo) {
  logo.addEventListener('click', function(e) {
    if (!sidebar.classList.contains('expanded')) {
      e.preventDefault();
      sidebar.classList.add('expanded');
    } else {
      window.location.href = '../Admin Dashboard/admin dashboard.html';
    }
  });
}

// Handle submenu toggling and navigation
sidebar.addEventListener('click', function(e) {
  const menu = e.target.closest('.sidebar-item-with-submenu');
  const link = e.target.closest('a');
  
  // If sidebar is collapsed, only allow expansion
  if (!sidebar.classList.contains('expanded')) {
    e.preventDefault();
    sidebar.classList.add('expanded');
    return;
  }
  
  // Handle submenu toggling when expanded
  if (menu && sidebar.contains(menu)) {
    const submenu = menu.nextElementSibling;
    if (submenu && submenu.classList.contains('submenu')) {
      // Close other submenus first
      const allSubmenus = document.querySelectorAll('.submenu');
      const allMenuItems = document.querySelectorAll('.sidebar-item-with-submenu');
      allSubmenus.forEach(sub => {
        if (sub !== submenu) {
          sub.classList.remove('open');
        }
      });
      allMenuItems.forEach(item => {
        if (item !== menu) {
          item.classList.remove('open');
        }
      });
      
      // Toggle current submenu
      submenu.classList.toggle('open');
      menu.classList.toggle('open');
    }
  }
  
  // Handle navigation links only when expanded
  if (link && sidebar.contains(link)) {
    // Allow navigation only if sidebar is expanded
    if (!sidebar.classList.contains('expanded')) {
      e.preventDefault();
    }
  }
});