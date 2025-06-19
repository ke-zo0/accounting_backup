// Modal logic for Add/Edit Schedule
const modal = document.getElementById('scheduleModal');
const openAddBtn = document.getElementById('openAddModal');
const closeModalBtn = document.getElementById('closeModal');
const modalTitle = document.getElementById('modalTitle');
const modalSaveBtn = document.getElementById('modalSaveBtn');
const scheduleForm = document.getElementById('scheduleForm');
const scheduleIdInput = document.getElementById('schedule_id');
const employeeIdInput = document.getElementById('employee_id');
const nameInput = document.getElementById('name');
const positionInput = document.getElementById('position');
const scheduleInput = document.getElementById('schedule');
// Open Add Modal
openAddBtn.onclick = function() {
  modalTitle.textContent = 'New Schedule';
  modalSaveBtn.textContent = 'Add Schedule';
  modalSaveBtn.name = 'add_schedule';
  scheduleIdInput.value = '';
  employeeIdInput.value = '';
  nameInput.value = '';
  positionInput.value = '';
  scheduleInput.value = '';
  modal.classList.add('active');
};
// Open Edit Modal
Array.from(document.querySelectorAll('.edit-btn')).forEach(btn => {
  btn.onclick = function() {
    modalTitle.textContent = 'Edit Schedule';
    modalSaveBtn.textContent = 'Save Changes';
    modalSaveBtn.name = 'edit_schedule';
    scheduleIdInput.value = btn.getAttribute('data-id');
    employeeIdInput.value = btn.getAttribute('data-employeeid');
    nameInput.value = btn.getAttribute('data-name');
    positionInput.value = btn.getAttribute('data-position');
    scheduleInput.value = btn.getAttribute('data-schedule');
    modal.classList.add('active');
  };
});
// Close Modal
closeModalBtn.onclick = function() {
  modal.classList.remove('active');
};
modal.onclick = function(e) {
  if (e.target === modal) modal.classList.remove('active');
}; 