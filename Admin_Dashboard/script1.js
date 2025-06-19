const projects = [
  { name: 'Modern Branding Kit for Startup Businesses', progress: 80 },
  { name: 'Mobile App UI/UX Concept: A New Way to Order Food Online', progress: 65 },
  { name: 'Weather Dashboard: Fetching Live Data Using OpenWeather API', progress: 90 },
  { name: 'Full-Stack E-Commerce Website with Payment Integration', progress: 50 },
  { name: 'RecipeMaster: A Recipe Finder App for iOS', progress: 80 },
  { name: 'ChatConnect: A Real-Time Messaging App Using Firebase', progress: 40 }
];

const tableBody = document.getElementById('project-list');

tableBody.innerHTML = '';

projects.forEach((project, idx) => {
  const row = document.createElement('tr');
  row.innerHTML = `
    <td>${project.name}</td>
    <td>
      <div class="progress-bar">
        <div class="progress-fill" style="width: ${project.progress}%;"></div>
      </div>
    </td>
    <td style="position:relative;">
      <button class="action-btn" data-idx="${idx}">⋮</button>
      <div class="action-menu" style="display:none; position:absolute; right:0; top:2rem; background:#fff; border:1px solid #ccc; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,0.08); z-index:10;">
        <div class="action-menu-item" data-action="view">View/Edit</div>
        <div class="action-menu-item" data-action="delete">Delete</div>
      </div>
    </td>
  `;
  tableBody.appendChild(row);
});

tableBody.addEventListener('click', function(e) {
  if (e.target.classList.contains('action-btn')) {
    // Toggle menu
    const btn = e.target;
    const menu = btn.nextElementSibling;
    document.querySelectorAll('.action-menu').forEach(m => { if (m !== menu) m.style.display = 'none'; });
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
  } else if (e.target.classList.contains('action-menu-item')) {
    const action = e.target.getAttribute('data-action');
    const row = e.target.closest('tr');
    const idx = Array.from(tableBody.children).indexOf(row);
    if (action === 'view') {
      const projectName = projects[idx].name;
      window.location.href = `project_details.php?name=${encodeURIComponent(projectName)}`;
    } else if (action === 'delete') {
      if (confirm('Are you sure you want to delete this project?')) {
        projects.splice(idx, 1);
        tableBody.removeChild(row);
      }
    }
    // Hide menu after action
    e.target.parentElement.style.display = 'none';
  } else {
    // Hide all menus if clicking elsewhere
    document.querySelectorAll('.action-menu').forEach(m => m.style.display = 'none');
  }
});

document.addEventListener('click', function(e) {
  if (!e.target.classList.contains('action-btn')) {
    document.querySelectorAll('.action-menu').forEach(m => m.style.display = 'none');
  }
});
