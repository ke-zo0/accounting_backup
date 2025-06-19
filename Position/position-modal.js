// Modal logic for Add/Edit Position
const modal = document.getElementById('positionModal');
const openAddBtn = document.getElementById('openAddModal');
const closeModalBtn = document.getElementById('closeModal');
const modalTitle = document.getElementById('modalTitle');
const modalSaveBtn = document.getElementById('modalSaveBtn');
const positionForm = document.getElementById('positionForm');
const positionIdInput = document.getElementById('position_id');
const positionTitleInput = document.getElementById('position_title');
const ratePerHourInput = document.getElementById('rate_per_hour');
// Open Add Modal
openAddBtn.onclick = function() {
  modalTitle.textContent = 'New Position';
  modalSaveBtn.textContent = 'Add Position';
  modalSaveBtn.name = 'add_position';
  positionIdInput.value = '';
  positionTitleInput.value = '';
  ratePerHourInput.value = '';
  modal.classList.add('active');
};
// Open Edit Modal
Array.from(document.querySelectorAll('.edit-btn')).forEach(btn => {
  btn.onclick = function() {
    modalTitle.textContent = 'Edit Position';
    modalSaveBtn.textContent = 'Save Changes';
    modalSaveBtn.name = 'edit_position';
    positionIdInput.value = btn.getAttribute('data-id');
    positionTitleInput.value = btn.getAttribute('data-title');
    ratePerHourInput.value = btn.getAttribute('data-rate');
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